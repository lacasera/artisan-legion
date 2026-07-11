<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "pest()" function to bind different classes or traits.
|
*/

pest()->extend(TestCase::class)
    ->use(RefreshDatabase::class)
    ->in('Feature');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

/**
 * A canned GitHub GraphQL user payload for Http::fake().
 *
 * @param  array<string, mixed>  $overrides
 * @return array<string, mixed>
 */
function githubUserResponse(array $overrides = []): array
{
    $repositories = $overrides['repositories'] ?? null;
    $manifests = $overrides['manifests'] ?? null;
    unset($overrides['repositories'], $overrides['manifests']);

    $user = array_replace_recursive([
        'login' => 'taylorotwell',
        'name' => 'Taylor Otwell',
        'avatarUrl' => 'https://github.com/taylorotwell.png',
        'location' => 'Little Rock, USA',
        'followers' => ['totalCount' => 8000],
        'contributionsCollection' => [
            'contributionCalendar' => ['totalContributions' => 3200],
        ],
        'repositories' => [
            'nodes' => [
                [
                    'stargazerCount' => 30000,
                    'pushedAt' => now()->subDays(2)->toIso8601String(),
                    'languages' => [
                        'edges' => [
                            ['size' => 5000000, 'node' => ['name' => 'PHP']],
                            ['size' => 800000, 'node' => ['name' => 'Blade']],
                        ],
                    ],
                ],
                [
                    'stargazerCount' => 4000,
                    'pushedAt' => now()->subDays(10)->toIso8601String(),
                    'languages' => [
                        'edges' => [
                            ['size' => 600000, 'node' => ['name' => 'JavaScript']],
                            ['size' => 90000, 'node' => ['name' => 'Shell']],
                        ],
                    ],
                ],
            ],
        ],
        'manifests' => [
            'nodes' => [
                [
                    'composerJson' => ['text' => json_encode(['require' => ['laravel/framework' => '^13.0', 'livewire/livewire' => '^4.0']])],
                    'packageJson' => ['text' => json_encode(['dependencies' => ['tailwindcss' => '^4.0']])],
                ],
                [
                    'composerJson' => ['text' => json_encode(['require' => ['laravel/framework' => '^12.0']])],
                    'packageJson' => null,
                ],
            ],
        ],
    ], $overrides);

    if ($repositories !== null) {
        $user['repositories'] = $repositories;
    }

    if ($manifests !== null) {
        $user['manifests'] = $manifests;
    }

    return ['data' => ['user' => $user]];
}
