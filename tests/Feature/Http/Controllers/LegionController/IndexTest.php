<?php

declare(strict_types=1);

use App\Models\Dev;
use Inertia\Testing\AssertableInertia as Assert;

it('renders_an_empty_legions_index', function () {
    $this->get(route('legions.index'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('legions/index')
            ->count('legions', 0));
});

it('ranks_legions_by_top_xi_average_ovr', function () {
    Dev::factory()->count(2)->create(['nation' => 'GHA', 'ovr' => 90, 'position' => 'CM']);
    Dev::factory()->create(['nation' => 'FRA', 'ovr' => 50, 'position' => 'CB', 'username' => 'baguette']);
    Dev::factory()->freeAgent()->create(['ovr' => 70, 'position' => 'ST']);

    $this->get(route('legions.index'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->count('legions', 3)
            ->where('legions.0.code', 'GHA')
            ->where('legions.0.soldiers', 2)
            ->where('legions.0.averageOvr', '90.0')
            ->where('legions.1.code', 'WLD')
            ->where('legions.2.code', 'FRA')
            ->where('legions.2.topSoldier', 'baguette'));
});
