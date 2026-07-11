<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Http;
use Inertia\Testing\AssertableInertia as Assert;

beforeEach(function () {
    Http::preventStrayRequests();
});

it('renders_the_card_page_with_the_struck_card', function () {
    Http::fake(['api.github.com/graphql' => Http::response(githubUserResponse())]);

    $this->get(route('cards.show', ['username' => 'taylorotwell']))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('cards/show')
            ->where('username', 'taylorotwell')
            ->where('dev.handle', 'taylorotwell')
            ->where('dev.nation', 'USA')
            ->missing('dev.internal_id')
            ->where('og.image', route('cards.image', ['username' => 'taylorotwell'])));
});

it('renders_the_ghost_state_when_there_is_not_enough_activity', function () {
    Http::fake([
        'api.github.com/graphql' => Http::response(githubUserResponse([
            'contributionsCollection' => ['contributionCalendar' => ['totalContributions' => 2]],
            'repositories' => ['nodes' => []],
        ])),
    ]);

    $this->get(route('cards.show', ['username' => 'ghost-account']))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('cards/show')
            ->where('dev', null));
});

it('renders_the_ghost_state_for_an_unknown_user', function () {
    Http::fake(['api.github.com/graphql' => Http::response(['data' => ['user' => null]])]);

    $this->get(route('cards.show', ['username' => 'nope']))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page->where('dev', null));
});

it('embeds_open_graph_meta_tags_for_link_unfurls', function () {
    Http::fake(['api.github.com/graphql' => Http::response(githubUserResponse())]);

    $this->get(route('cards.show', ['username' => 'taylorotwell']))
        ->assertOk()
        ->assertSee('property="og:image"', false)
        ->assertSee(route('cards.image', ['username' => 'taylorotwell']), false)
        ->assertSee('name="twitter:card"', false);
});
