<?php

declare(strict_types=1);

use App\Data\GitHubProfileData;
use App\Services\RatingEngine;
use Tests\TestCase;

uses(TestCase::class);

/**
 * @param  array<string, array{bytes: int, stars: int, recent: bool}>  $languages
 */
function profileWith(array $languages, int $contributions = 1200, int $stars = 500, int $followers = 100): GitHubProfileData
{
    return new GitHubProfileData(
        login: 'someone',
        name: 'Someone',
        avatarUrl: null,
        location: null,
        followers: $followers,
        totalContributions: $contributions,
        totalStars: $stars,
        languages: $languages,
    );
}

it('rates_a_ghost_profile_as_null_when_no_languages_exist', function () {
    $engine = new RatingEngine;

    expect($engine->rate(profileWith([])))->toBeNull();
});

it('rates_a_ghost_profile_as_null_when_activity_is_negligible', function () {
    $engine = new RatingEngine;
    $profile = profileWith(
        ['PHP' => ['bytes' => 5000, 'stars' => 0, 'recent' => false]],
        contributions: 3,
        stars: 0,
    );

    expect($engine->rate($profile))->toBeNull();
});

it('rates_a_dev_with_a_real_codebase_but_a_quiet_public_year', function () {
    $engine = new RatingEngine;
    // Rich language footprint, but few public contributions and no stars this
    // year (works mostly on private repos) — must still get a real card.
    $profile = profileWith([
        'PHP' => ['bytes' => 376712, 'stars' => 0, 'recent' => false],
        'Vue' => ['bytes' => 106815, 'stars' => 0, 'recent' => false],
        'Blade' => ['bytes' => 41594, 'stars' => 0, 'recent' => false],
        'TypeScript' => ['bytes' => 8530, 'stars' => 0, 'recent' => false],
    ], contributions: 4, stars: 0, followers: 19);

    expect($engine->isGhost($profile))->toBeFalse()
        ->and($engine->rate($profile))->not->toBeNull();
});

it('scores_the_dominant_language_highest_and_keeps_all_scores_in_range', function () {
    $engine = new RatingEngine;
    $profile = profileWith([
        'PHP' => ['bytes' => 5000000, 'stars' => 30000, 'recent' => true],
        'Blade' => ['bytes' => 800000, 'stars' => 5000, 'recent' => true],
        'JavaScript' => ['bytes' => 600000, 'stars' => 2000, 'recent' => true],
        'Shell' => ['bytes' => 100000, 'stars' => 100, 'recent' => false],
    ], contributions: 3000, stars: 40000, followers: 8000);

    $stats = $engine->languageStats($profile);
    $values = array_map(fn ($stat) => $stat->val, $stats);

    expect($stats[0]->name)->toBe('PHP')
        ->and($values)->toBe(collect($values)->sortDesc()->values()->all())
        ->and(min($values))->toBeGreaterThanOrEqual(40)
        ->and(max($values))->toBeLessThanOrEqual(99);
});

it('keeps_the_stack_to_at_most_four_languages', function () {
    $engine = new RatingEngine;
    $languages = collect(range(1, 8))->mapWithKeys(fn (int $i) => [
        "Lang{$i}" => ['bytes' => 100000 * $i, 'stars' => 10 * $i, 'recent' => true],
    ])->all();

    expect($engine->languageStats(profileWith($languages)))->toHaveCount(4);
});

it('awards_gold_overall_to_a_heavyweight_profile', function () {
    $engine = new RatingEngine;
    $rating = $engine->rate(profileWith([
        'PHP' => ['bytes' => 5000000, 'stars' => 30000, 'recent' => true],
        'Blade' => ['bytes' => 800000, 'stars' => 5000, 'recent' => true],
        'JavaScript' => ['bytes' => 600000, 'stars' => 2000, 'recent' => true],
        'Shell' => ['bytes' => 100000, 'stars' => 100, 'recent' => false],
    ], contributions: 3000, stars: 40000, followers: 8000));

    expect($rating->ovr)->toBeGreaterThanOrEqual(90)
        ->and($rating->ovr)->toBeLessThanOrEqual(99);
});

it('keeps_a_modest_profile_below_gold', function () {
    $engine = new RatingEngine;
    $rating = $engine->rate(profileWith([
        'Python' => ['bytes' => 90000, 'stars' => 12, 'recent' => true],
        'Shell' => ['bytes' => 15000, 'stars' => 0, 'recent' => false],
    ], contributions: 240, stars: 15, followers: 20));

    expect($rating->ovr)->toBeGreaterThanOrEqual(40)
        ->and($rating->ovr)->toBeLessThan(90);
});

it('derives_striker_for_prolific_shippers', function () {
    $engine = new RatingEngine;
    $profile = profileWith(
        ['PHP' => ['bytes' => 100000, 'stars' => 10, 'recent' => true]],
        contributions: 6000,
    );

    expect($engine->position($profile))->toBe('ST');
});

it('does_not_derive_striker_below_the_shipping_threshold', function () {
    $engine = new RatingEngine;
    $profile = profileWith(
        ['PHP' => ['bytes' => 100000, 'stars' => 10, 'recent' => true]],
        contributions: 4000,
    );

    expect($engine->position($profile))->toBe('CDM');
});

it('derives_cdm_for_backend_heavy_stacks', function () {
    $engine = new RatingEngine;
    $profile = profileWith([
        'PHP' => ['bytes' => 900000, 'stars' => 100, 'recent' => true],
        'JavaScript' => ['bytes' => 100000, 'stars' => 10, 'recent' => true],
    ], contributions: 800);

    expect($engine->position($profile))->toBe('CDM');
});

it('derives_goalkeeper_for_infra_heavy_stacks', function () {
    $engine = new RatingEngine;
    $profile = profileWith([
        'Shell' => ['bytes' => 400000, 'stars' => 50, 'recent' => true],
        'Dockerfile' => ['bytes' => 200000, 'stars' => 20, 'recent' => true],
        'PHP' => ['bytes' => 400000, 'stars' => 10, 'recent' => true],
    ], contributions: 500);

    expect($engine->position($profile))->toBe('GK');
});

it('explains_a_rating_with_totals_shares_and_position_rule', function () {
    $engine = new RatingEngine;
    $profile = profileWith([
        'PHP' => ['bytes' => 900000, 'stars' => 100, 'recent' => true],
        'JavaScript' => ['bytes' => 100000, 'stars' => 10, 'recent' => false],
    ], contributions: 800, stars: 110, followers: 40);

    $breakdown = $engine->explain($profile);

    expect($breakdown)->not->toBeNull()
        ->and($breakdown->contributions)->toBe(800)
        ->and($breakdown->stars)->toBe(110)
        ->and($breakdown->followers)->toBe(40)
        ->and($breakdown->position)->toBe('CDM')
        ->and($breakdown->positionRule)->toContain('backend')
        ->and($breakdown->ovr)->toBe($engine->rate($profile)->ovr)
        ->and($breakdown->languages->toCollection())->toHaveCount(2)
        ->and($breakdown->languages->toCollection()->sum('sharePct'))->toBe(100)
        ->and($breakdown->languages->toCollection()->first()->name)->toBe('PHP');
});

it('explains_the_striker_rule_for_prolific_shippers', function () {
    $engine = new RatingEngine;
    $profile = profileWith(
        ['PHP' => ['bytes' => 100000, 'stars' => 10, 'recent' => true]],
        contributions: 6000,
    );

    expect($engine->explain($profile)->positionRule)->toContain('prolific shipper');
});

it('returns_no_breakdown_for_a_ghost', function () {
    $engine = new RatingEngine;

    expect($engine->explain(profileWith([])))->toBeNull();
});

it('derives_cam_for_broad_balanced_stacks', function () {
    $engine = new RatingEngine;
    $profile = profileWith([
        'PHP' => ['bytes' => 300000, 'stars' => 10, 'recent' => true],
        'TypeScript' => ['bytes' => 280000, 'stars' => 10, 'recent' => true],
        'Rust' => ['bytes' => 250000, 'stars' => 10, 'recent' => true],
        'Swift' => ['bytes' => 240000, 'stars' => 10, 'recent' => true],
    ], contributions: 900);

    expect($engine->position($profile))->toBe('CAM');
});
