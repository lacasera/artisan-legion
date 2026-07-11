<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Queue;
use Inertia\Testing\AssertableInertia as Assert;

it('renders_the_themed_404_page_for_unknown_routes', function () {
    $this->get('/no/such/front')
        ->assertNotFound()
        ->assertInertia(fn (Assert $page) => $page
            ->component('error')
            ->where('status', 404));
});

it('renders_the_themed_404_page_for_unmustered_legions', function () {
    $this->get(route('legions.show', ['code' => 'GHA']))
        ->assertNotFound()
        ->assertInertia(fn (Assert $page) => $page->component('error'));
});

it('renders_the_themed_429_page_when_rate_limited', function () {
    Queue::fake();

    foreach (range(1, 10) as $attempt) {
        $this->get(route('lookup', ['username' => "dev{$attempt}"]))->assertOk();
    }

    $this->get(route('lookup', ['username' => 'dev11']))
        ->assertStatus(429)
        ->assertInertia(fn (Assert $page) => $page
            ->component('error')
            ->where('status', 429));
});
