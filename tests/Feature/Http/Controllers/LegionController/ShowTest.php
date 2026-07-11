<?php

declare(strict_types=1);

use App\Models\Dev;
use Inertia\Testing\AssertableInertia as Assert;

it('returns_404_for_a_legion_with_no_soldiers', function () {
    $this->get(route('legions.show', ['code' => 'GHA']))->assertNotFound();
});

it('renders_a_legion_with_formation_captain_and_reserves', function () {
    Dev::factory()->create(['nation' => 'GHA', 'ovr' => 99, 'position' => 'CB', 'username' => 'top-soldier'])
        ->languages()->create(['language' => 'PHP', 'score' => 97, 'rank' => 1]);
    Dev::factory()->count(12)->create(['nation' => 'GHA', 'ovr' => 70, 'position' => 'CB']);

    $this->get(route('legions.show', ['code' => 'GHA']))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('legions/show')
            ->where('legion.code', 'GHA')
            ->where('legion.soldierCount', 13)
            ->where('legion.rank', 1)
            ->count('legion.defense', 4)
            ->count('legion.midfield', 3)
            ->count('legion.attack', 3)
            ->count('legion.goalkeeper', 1)
            ->count('legion.reserves', 2)
            ->where('legion.captain.handle', 'top-soldier')
            ->where('legion.defense.0.captain', true)
            ->where('legion.defense.0.topLanguage', 'PHP'));
});

it('places_players_in_their_natural_rows', function () {
    Dev::factory()->create(['nation' => 'FRA', 'ovr' => 80, 'position' => 'GK', 'username' => 'keeper']);
    Dev::factory()->create(['nation' => 'FRA', 'ovr' => 85, 'position' => 'ST', 'username' => 'striker']);
    Dev::factory()->create(['nation' => 'FRA', 'ovr' => 82, 'position' => 'CAM', 'username' => 'playmaker']);

    $this->get(route('legions.show', ['code' => 'FRA']))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->where('legion.goalkeeper.0.handle', 'keeper')
            ->where('legion.attack.0.handle', 'striker')
            ->where('legion.midfield.0.handle', 'playmaker')
            ->where('legion.captain.handle', 'striker'));
});

it('buckets_free_agents_into_the_world_xi', function () {
    Dev::factory()->freeAgent()->create(['ovr' => 77, 'position' => 'CM', 'username' => 'wanderer']);

    $this->get(route('legions.show', ['code' => 'WLD']))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->where('legion.code', 'WLD')
            ->where('legion.soldierCount', 1)
            ->where('legion.midfield.0.handle', 'wanderer'));
});

it('accepts_a_lowercase_legion_code', function () {
    Dev::factory()->create(['nation' => 'GHA', 'ovr' => 70, 'position' => 'CM']);

    $this->get(route('legions.show', ['code' => 'gha']))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('legions/show')
            ->where('legion.code', 'GHA'));
});

it('counts_recent_enlistments_in_the_last_day', function () {
    Dev::factory()->create(['nation' => 'GHA', 'ovr' => 70, 'position' => 'CM']);
    Dev::factory()->create(['nation' => 'GHA', 'ovr' => 60, 'position' => 'CB', 'created_at' => now()->subDays(3)]);

    $this->get(route('legions.show', ['code' => 'GHA']))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page->where('legion.recentEnlistments', 1));
});
