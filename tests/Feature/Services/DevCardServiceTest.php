<?php

declare(strict_types=1);

use App\Models\Dev;
use App\Services\DevCardService;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    Http::preventStrayRequests();
});

it('strikes_a_card_and_persists_the_dev_with_languages', function () {
    Http::fake(['api.github.com/graphql' => Http::response(githubUserResponse())]);

    $card = app(DevCardService::class)->cardFor('taylorotwell');

    $dev = Dev::query()->where('username', 'taylorotwell')->firstOrFail();

    expect($card->id)->toBe($dev->public_id)
        ->and($card->handle)->toBe('taylorotwell')
        ->and($card->name)->toBe('TAYLOR OTWELL')
        ->and($card->frameworks)->toBe(['LARAVEL', 'LIVEWIRE', 'TAILWIND'])
        ->and($card->nation)->toBe('USA')
        ->and($card->rankLabel)->toBe('USA · NAT #1')
        ->and($card->serial)->toBe(str_pad((string) $dev->id, 5, '0', STR_PAD_LEFT))
        ->and($card->specialist)->toBeFalse()
        ->and($card->stats)->toHaveCount(4)
        ->and($dev->languages()->count())->toBe(4);
});

it('serves_repeat_lookups_from_cache_without_a_second_api_call', function () {
    Http::fake(['api.github.com/graphql' => Http::response(githubUserResponse())]);

    $service = app(DevCardService::class);
    $first = $service->cardFor('taylorotwell');
    $second = $service->cardFor('taylorotwell');

    expect($second->id)->toBe($first->id);
    Http::assertSentCount(1);
});

it('returns_null_for_a_ghost_account_and_does_not_persist_it', function () {
    Http::fake([
        'api.github.com/graphql' => Http::response(githubUserResponse([
            'contributionsCollection' => ['contributionCalendar' => ['totalContributions' => 2]],
            'repositories' => ['nodes' => []],
        ])),
    ]);

    expect(app(DevCardService::class)->cardFor('ghost-account'))->toBeNull()
        ->and(Dev::query()->count())->toBe(0);
});

it('returns_null_for_an_unknown_user', function () {
    Http::fake(['api.github.com/graphql' => Http::response(['data' => ['user' => null]])]);

    expect(app(DevCardService::class)->cardFor('nope'))->toBeNull();
});

it('falls_back_to_the_persisted_card_when_rate_limited', function () {
    /** @var Dev $dev */
    $dev = Dev::factory()->gold()->create(['username' => 'taylorotwell', 'nation' => 'USA']);
    $dev->languages()->create(['language' => 'PHP', 'score' => 97, 'rank' => 1]);

    Http::fake(['api.github.com/graphql' => Http::response(['message' => 'rate limited'], 403)]);

    $card = app(DevCardService::class)->cardFor('taylorotwell');

    expect($card)->not->toBeNull()
        ->and($card->id)->toBe($dev->public_id)
        ->and($card->specialist)->toBeTrue();
});

it('degrades_to_null_instead_of_erroring_when_github_is_unreachable', function () {
    Http::fake(['api.github.com/graphql' => Http::response(['message' => 'bad credentials'], 401)]);

    expect(app(DevCardService::class)->cardFor('taylorotwell'))->toBeNull();
});

it('marks_single_language_devs_as_specialists', function () {
    Http::fake([
        'api.github.com/graphql' => Http::response(githubUserResponse([
            'repositories' => [
                'nodes' => [
                    [
                        'stargazerCount' => 9000,
                        'pushedAt' => now()->subDays(3)->toIso8601String(),
                        'languages' => [
                            'edges' => [
                                ['size' => 2000000, 'node' => ['name' => 'PHP']],
                            ],
                        ],
                    ],
                    [
                        'stargazerCount' => 0,
                        'pushedAt' => null,
                        'languages' => ['edges' => []],
                    ],
                ],
            ],
        ])),
    ]);

    $card = app(DevCardService::class)->cardFor('fabpot');

    expect($card->specialist)->toBeTrue()
        ->and($card->stats)->toHaveCount(1);
});
