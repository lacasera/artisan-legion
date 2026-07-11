<?php

declare(strict_types=1);

use App\Models\Dev;
use App\Models\WeeklyScore;
use App\Services\WeeklyWarService;
use Inertia\Testing\AssertableInertia as Assert;

it('renders_the_landing_page', function () {
    $this->get(route('home'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('home')
            ->count('ticker', 0)
            ->where('soldierCount', 0));
});

it('feeds_the_ticker_and_soldier_count_from_real_data', function () {
    $dev = Dev::factory()->create(['nation' => 'GHA']);
    Dev::factory()->create(['nation' => 'USA']);
    WeeklyScore::factory()->create([
        'dev_id' => $dev->id,
        'nation' => 'GHA',
        'week' => app(WeeklyWarService::class)->weekKey(),
        'points' => 100,
    ]);

    $this->get(route('home'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->where('soldierCount', 2)
            ->count('ticker', 1)
            ->where('ticker.0.code', 'GHA'));
});
