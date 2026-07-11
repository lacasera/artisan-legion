<?php

declare(strict_types=1);

use App\Models\Dev;
use App\Models\WeeklyScore;
use App\Services\WeeklyWarService;

it('awards_ovr_weighted_points_for_commits', function () {
    /** @var Dev $dev */
    $dev = Dev::factory()->create(['ovr' => 50, 'nation' => 'GHA']);

    $awarded = app(WeeklyWarService::class)->awardCommits($dev, 5);

    expect($awarded)->toBe(50);
    $score = WeeklyScore::query()->firstOrFail();
    expect($score->points)->toBe(50)
        ->and($score->day_commits)->toBe(5)
        ->and($score->nation)->toBe('GHA')
        ->and($score->week)->toBe(app(WeeklyWarService::class)->weekKey());
});

it('weights_points_by_ovr', function () {
    /** @var Dev $dev */
    $dev = Dev::factory()->create(['ovr' => 90, 'nation' => 'GHA']);

    expect(app(WeeklyWarService::class)->awardCommits($dev, 5))->toBe(90);
});

it('applies_diminishing_returns_beyond_thirty_commits_a_day', function () {
    /** @var Dev $dev */
    $dev = Dev::factory()->create(['ovr' => 50, 'nation' => 'GHA']);

    $awarded = app(WeeklyWarService::class)->awardCommits($dev, 40);

    expect($awarded)->toBe(325);
});

it('stops_awarding_beyond_the_daily_ceiling', function () {
    /** @var Dev $dev */
    $dev = Dev::factory()->create(['ovr' => 50, 'nation' => 'GHA']);
    $service = app(WeeklyWarService::class);

    $first = $service->awardCommits($dev, 150);
    $second = $service->awardCommits($dev, 10);

    expect($first)->toBe(475)
        ->and($second)->toBe(0)
        ->and(WeeklyScore::query()->firstOrFail()->points)->toBe(475);
});

it('accumulates_awards_in_a_single_weekly_row', function () {
    /** @var Dev $dev */
    $dev = Dev::factory()->create(['ovr' => 50, 'nation' => 'GHA']);
    $service = app(WeeklyWarService::class);

    $service->awardCommits($dev, 3);
    $service->awardCommits($dev, 2);

    expect(WeeklyScore::query()->count())->toBe(1)
        ->and(WeeklyScore::query()->firstOrFail()->points)->toBe(50);
});

it('awards_nothing_for_non_positive_deltas', function () {
    /** @var Dev $dev */
    $dev = Dev::factory()->create(['ovr' => 50, 'nation' => 'GHA']);

    expect(app(WeeklyWarService::class)->awardCommits($dev, 0))->toBe(0)
        ->and(WeeklyScore::query()->count())->toBe(0);
});

it('builds_a_ranked_board_grouped_by_nation_including_the_world_xi', function () {
    $week = app(WeeklyWarService::class)->weekKey();
    $ghanaTop = Dev::factory()->create(['nation' => 'GHA', 'username' => 'kwame', 'last_active_at' => now()]);
    $ghanaSecond = Dev::factory()->create(['nation' => 'GHA', 'username' => 'ama', 'last_active_at' => null]);
    $american = Dev::factory()->create(['nation' => 'USA', 'username' => 'sam', 'last_active_at' => now()->subDays(2)]);
    $freeAgent = Dev::factory()->freeAgent()->create(['username' => 'wanderer']);

    WeeklyScore::factory()->create(['dev_id' => $ghanaTop->id, 'nation' => 'GHA', 'week' => $week, 'points' => 500, 'day_points' => 100]);
    WeeklyScore::factory()->create(['dev_id' => $ghanaSecond->id, 'nation' => 'GHA', 'week' => $week, 'points' => 300, 'day_points' => 0]);
    WeeklyScore::factory()->create(['dev_id' => $american->id, 'nation' => 'USA', 'week' => $week, 'points' => 600, 'day_points' => 0]);
    WeeklyScore::factory()->create(['dev_id' => $freeAgent->id, 'nation' => null, 'week' => $week, 'points' => 200, 'day_points' => 0]);

    $board = app(WeeklyWarService::class)->board()->toCollection();

    expect($board)->toHaveCount(3)
        ->and($board[0]->code)->toBe('GHA')
        ->and($board[0]->points)->toBe(800)
        ->and($board[0]->rank)->toBe(1)
        ->and($board[0]->pct)->toBe(100)
        ->and($board[0]->topSoldier)->toBe('kwame')
        ->and($board[0]->pushing)->toBeTrue()
        ->and($board[1]->code)->toBe('USA')
        ->and($board[1]->pushing)->toBeFalse()
        ->and($board[2]->code)->toBe('WLD')
        ->and($board[2]->pct)->toBe(25);
});

it('reports_rank_movement_relative_to_the_start_of_the_day', function () {
    $week = app(WeeklyWarService::class)->weekKey();
    $ghanaian = Dev::factory()->create(['nation' => 'GHA']);
    $american = Dev::factory()->create(['nation' => 'USA']);

    WeeklyScore::factory()->create(['dev_id' => $ghanaian->id, 'nation' => 'GHA', 'week' => $week, 'points' => 700, 'day_points' => 700, 'day' => now('UTC')->toDateString()]);
    WeeklyScore::factory()->create(['dev_id' => $american->id, 'nation' => 'USA', 'week' => $week, 'points' => 600, 'day_points' => 0, 'day' => now('UTC')->toDateString()]);

    $board = app(WeeklyWarService::class)->board()->toCollection();

    expect($board[0]->code)->toBe('GHA')
        ->and($board[0]->move)->toBe('▲1')
        ->and($board[1]->code)->toBe('USA')
        ->and($board[1]->move)->toBe('▼1');
});

it('ignores_scores_from_previous_weeks', function () {
    $dev = Dev::factory()->create(['nation' => 'GHA']);
    WeeklyScore::factory()->create(['dev_id' => $dev->id, 'nation' => 'GHA', 'week' => '2020-01-05', 'points' => 900]);

    expect(app(WeeklyWarService::class)->board()->toCollection())->toBeEmpty();
});
