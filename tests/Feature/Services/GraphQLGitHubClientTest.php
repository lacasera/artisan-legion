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

it('counts_only_public_contributions', function () {
    Http::fake([
        'api.github.com/graphql' => Http::response(githubUserResponse([
            'contributionsCollection' => [
                'restrictedContributionsCount' => 700,
                'contributionCalendar' => ['totalContributions' => 3200],
            ],
        ])),
    ]);

    expect(app(GraphQLGitHubClient::class)->fetchProfile('taylorotwell')->totalContributions)->toBe(2500);
});

it('counts_only_public_contributions_when_polling_the_war', function () {
    Http::fake([
        'api.github.com/graphql' => Http::response([
            'data' => [
                'user' => [
                    'contributionsCollection' => [
                        'restrictedContributionsCount' => 40,
                        'contributionCalendar' => ['totalContributions' => 1240],
                    ],
                ],
            ],
        ]),
    ]);

    expect(app(GraphQLGitHubClient::class)->fetchContributionCount('taylorotwell'))->toBe(1200);
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
