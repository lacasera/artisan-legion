<?php

declare(strict_types=1);

use Inertia\Testing\AssertableInertia as Assert;

it('renders_the_weekly_war_page', function () {
    $this->get(route('war'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page->component('war'));
});
