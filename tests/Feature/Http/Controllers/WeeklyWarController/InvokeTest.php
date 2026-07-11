<?php

declare(strict_types=1);

use App\Models\Dev;
use App\Models\WeeklyScore;
use App\Services\WeeklyWarService;
use Inertia\Testing\AssertableInertia as Assert;

it('renders_an_empty_war_board', function () {
    $this->get(route('war'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('war')
            ->count('board', 0)
            ->has('pushingCount')
            ->has('resetsAt')
            ->has('weekLabel'));
});

it('renders_the_live_board_with_ranked_legions', function () {
    $dev = Dev::factory()->create(['nation' => 'GHA', 'username' => 'kwame']);
    WeeklyScore::factory()->create([
        'dev_id' => $dev->id,
        'nation' => 'GHA',
        'week' => app(WeeklyWarService::class)->weekKey(),
        'points' => 420,
    ]);

    $this->get(route('war'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('war')
            ->count('board', 1)
            ->where('board.0.code', 'GHA')
            ->where('board.0.rank', 1)
            ->where('board.0.points', 420)
            ->where('board.0.topSoldier', 'kwame'));
});
