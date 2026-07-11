<?php

declare(strict_types=1);

use Inertia\Testing\AssertableInertia as Assert;

it('renders_the_legions_index_page', function () {
    $this->get(route('legions.index'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page->component('legions/index'));
});
