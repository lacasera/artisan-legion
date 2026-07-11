<?php

declare(strict_types=1);

use App\Services\GitHub\GitHubRateLimitedException;
use App\Services\GitHub\GraphQLGitHubClient;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    Http::preventStrayRequests();
});

it('fetches_and_aggregates_a_profile', function () {
    Http::fake(['api.github.com/graphql' => Http::response(githubUserResponse())]);

    $profile = app(GraphQLGitHubClient::class)->fetchProfile('taylorotwell');

    expect($profile->login)->toBe('taylorotwell')
        ->and($profile->followers)->toBe(8000)
        ->and($profile->totalContributions)->toBe(3200)
        ->and($profile->totalStars)->toBe(34000)
        ->and(array_keys($profile->languages))->toBe(['PHP', 'Blade', 'JavaScript', 'Shell'])
        ->and($profile->languages['PHP'])->toBe(['bytes' => 5000000, 'stars' => 30000, 'recent' => true])
        ->and($profile->languages['JavaScript']['stars'])->toBe(4000)
        ->and($profile->languages['Shell']['stars'])->toBe(0);
});

it('infers_frameworks_from_dependency_manifests_ordered_by_repo_count', function () {
    Http::fake(['api.github.com/graphql' => Http::response(githubUserResponse())]);

    $profile = app(GraphQLGitHubClient::class)->fetchProfile('taylorotwell');

    expect($profile->frameworks)->toBe(['LARAVEL', 'LIVEWIRE', 'TAILWIND']);
});

it('handles_missing_or_malformed_manifests', function () {
    Http::fake([
        'api.github.com/graphql' => Http::response(githubUserResponse([
            'manifests' => [
                'nodes' => [
                    ['composerJson' => null, 'packageJson' => ['text' => 'not-json{{']],
                ],
            ],
        ])),
    ]);

    expect(app(GraphQLGitHubClient::class)->fetchProfile('taylorotwell')->frameworks)->toBe([]);
});

it('counts_the_full_contribution_calendar_including_opted_in_private', function () {
    // GitHub only puts private contributions in the calendar when the dev
    // opted in — so the calendar total is exactly what they chose to surface.
    Http::fake([
        'api.github.com/graphql' => Http::response(githubUserResponse([
            'contributionsCollection' => [
                'contributionCalendar' => ['totalContributions' => 3200],
            ],
        ])),
    ]);

    expect(app(GraphQLGitHubClient::class)->fetchProfile('taylorotwell')->totalContributions)->toBe(3200);
});

it('fetches_many_contribution_counts_in_one_aliased_request', function () {
    Http::preventStrayRequests();
    Http::fake([
        'api.github.com/graphql' => Http::response([
            'data' => [
                'u0' => ['contributionsCollection' => ['contributionCalendar' => ['totalContributions' => 1240]]],
                'u1' => ['contributionsCollection' => ['contributionCalendar' => ['totalContributions' => 87]]],
                'u2' => null,
            ],
        ]),
    ]);

    $counts = app(GraphQLGitHubClient::class)->fetchContributionCounts(['taylorotwell', 'themsaid', 'ghost-nobody']);

    expect($counts)->toBe(['taylorotwell' => 1240, 'themsaid' => 87]);
    Http::assertSentCount(1);
});

it('returns_an_empty_map_for_no_logins', function () {
    Http::preventStrayRequests();

    expect(app(GraphQLGitHubClient::class)->fetchContributionCounts([]))->toBe([]);
    Http::assertNothingSent();
});

it('returns_null_for_an_unknown_user', function () {
    Http::fake(['api.github.com/graphql' => Http::response(['data' => ['user' => null]])]);

    expect(app(GraphQLGitHubClient::class)->fetchProfile('nope'))->toBeNull();
});

it('throws_when_the_http_status_signals_rate_limiting', function () {
    Http::fake(['api.github.com/graphql' => Http::response(['message' => 'rate limited'], 403)]);

    app(GraphQLGitHubClient::class)->fetchProfile('taylorotwell');
})->throws(GitHubRateLimitedException::class);

it('throws_when_graphql_reports_rate_limiting', function () {
    Http::fake([
        'api.github.com/graphql' => Http::response([
            'data' => null,
            'errors' => [['type' => 'RATE_LIMITED', 'message' => 'API rate limit exceeded']],
        ]),
    ]);

    app(GraphQLGitHubClient::class)->fetchProfile('taylorotwell');
})->throws(GitHubRateLimitedException::class);

it('sends_the_configured_token', function () {
    config()->set('services.github.token', 'test-token');
    Http::fake(['api.github.com/graphql' => Http::response(githubUserResponse())]);

    app(GraphQLGitHubClient::class)->fetchProfile('taylorotwell');

    Http::assertSent(fn ($request) => $request->hasHeader('Authorization', 'Bearer test-token'));
});
