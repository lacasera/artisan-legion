<?php

declare(strict_types=1);

use Inertia\Testing\AssertableInertia as Assert;

it('renders_the_legion_page_with_an_uppercased_code', function () {
    $this->get(route('legions.show', ['code' => 'gha']))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('legions/show')
            ->where('code', 'GHA'));
});
