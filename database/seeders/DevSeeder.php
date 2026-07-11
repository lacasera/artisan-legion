<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Dev;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class DevSeeder extends Seeder
{
    private const array NATIONS = [
        'GHA', 'USA', 'IND', 'NGA', 'GER', 'BRA', 'FRA', 'JPN', 'POR', 'ARG', 'GBR', 'CAN',
    ];

    private const array LANGUAGES = [
        'PHP', 'JavaScript', 'TypeScript', 'Python', 'Go', 'Rust', 'Ruby', 'Java', 'Kotlin', 'Swift', 'Elixir', 'Shell',
    ];

    public function run(): void
    {
        Dev::factory()
            ->count(48)
            ->state(new Sequence(fn (Sequence $sequence) => [
                'nation' => self::NATIONS[$sequence->index % count(self::NATIONS)],
            ]))
            ->create()
            ->concat(Dev::factory()->freeAgent()->count(4)->create())
            ->each(function (Dev $dev) {
                $languages = collect(self::LANGUAGES)->shuffle()->take(fake()->numberBetween(1, 4));

                $languages->values()->each(fn (string $language, int $index) => $dev->languages()->create([
                    'language' => mb_strtoupper($language),
                    'score' => max(40, $dev->ovr + fake()->numberBetween(3, 8) - $index * 9),
                    'rank' => $index + 1,
                ]));
            });
    }
}
