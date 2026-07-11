<?php

declare(strict_types=1);

use App\Jobs\PollDevCommitsJob;
use App\Models\Dev;
use App\Models\WeeklyScore;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    Http::preventStrayRequests();
});

/**
 * @return array<string, mixed>
 */
function contributionsResponse(?int $total): array
{
    return [
        'data' => [
            'user' => $total === null ? null : [
                'contributionsCollection' => [
                    'contributionCalendar' => ['totalContributions' => $total],
                ],
            ],
        ],
    ];
}

it('sets_the_baseline_on_the_first_poll_without_awarding_points', function () {
    Http::fake(['api.github.com/graphql' => Http::response(contributionsResponse(1200))]);
    /** @var Dev $dev */
    $dev = Dev::factory()->create(['last_contribution_count' => null]);

    PollDevCommitsJob::dispatchSync($dev->id);

    $dev->refresh();
    expect($dev->last_contribution_count)->toBe(1200)
        ->and($dev->last_polled_at)->not->toBeNull()
        ->and($dev->last_active_at)->toBeNull()
        ->and(WeeklyScore::query()->count())->toBe(0);
});

it('awards_points_for_the_commit_delta_on_subsequent_polls', function () {
    Http::fake(['api.github.com/graphql' => Http::response(contributionsResponse(1205))]);
    /** @var Dev $dev */
    $dev = Dev::factory()->create(['ovr' => 50, 'nation' => 'GHA', 'last_contribution_count' => 1200]);

    PollDevCommitsJob::dispatchSync($dev->id);

    $dev->refresh();
    expect($dev->last_contribution_count)->toBe(1205)
        ->and($dev->last_active_at)->not->toBeNull()
        ->and(WeeklyScore::query()->firstOrFail()->points)->toBe(50);
});

it('never_awards_negative_deltas', function () {
    Http::fake(['api.github.com/graphql' => Http::response(contributionsResponse(900))]);
    /** @var Dev $dev */
    $dev = Dev::factory()->create(['last_contribution_count' => 1200]);

    PollDevCommitsJob::dispatchSync($dev->id);

    expect($dev->refresh()->last_contribution_count)->toBe(900)
        ->and(WeeklyScore::query()->count())->toBe(0);
});

it('leaves_the_baseline_untouched_for_unknown_users', function () {
    Http::fake(['api.github.com/graphql' => Http::response(contributionsResponse(null))]);
    /** @var Dev $dev */
    $dev = Dev::factory()->create(['last_contribution_count' => 1200]);

    PollDevCommitsJob::dispatchSync($dev->id);

    expect($dev->refresh()->last_contribution_count)->toBe(1200);
});

it('survives_rate_limiting_without_failing', function () {
    Http::fake(['api.github.com/graphql' => Http::response(['message' => 'rate limited'], 403)]);
    /** @var Dev $dev */
    $dev = Dev::factory()->create(['last_contribution_count' => 1200]);

    PollDevCommitsJob::dispatchSync($dev->id);

    expect($dev->refresh()->last_contribution_count)->toBe(1200)
        ->and(WeeklyScore::query()->count())->toBe(0);
});
