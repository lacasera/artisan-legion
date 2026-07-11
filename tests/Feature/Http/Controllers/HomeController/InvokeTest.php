<?php

declare(strict_types=1);

use Inertia\Testing\AssertableInertia as Assert;

it('renders_the_landing_page', function () {
    $this->get(route('home'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page->component('home'));
});
