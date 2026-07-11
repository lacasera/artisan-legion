<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Dev;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Dev>
 */
class DevFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'username' => fake()->unique()->userName(),
            'name' => fake()->name(),
            'avatar_url' => null,
            'location' => fake()->city(),
            'nation' => fake()->randomElement(['USA', 'GHA', 'FRA', 'IND', 'BRA']),
            'ovr' => fake()->numberBetween(40, 99),
            'position' => fake()->randomElement(['ST', 'CAM', 'CDM', 'CM', 'LW', 'RW', 'CB', 'GK']),
            'raw_stats' => [
                'followers' => fake()->numberBetween(10, 5000),
                'contributions' => fake()->numberBetween(50, 4000),
                'stars' => fake()->numberBetween(0, 20000),
                'languages' => collect(fake()->randomElements(['PHP', 'JavaScript', 'TypeScript', 'Python', 'Go', 'Rust'], fake()->numberBetween(2, 4)))
                    ->mapWithKeys(fn (string $language) => [$language => [
                        'bytes' => fake()->numberBetween(50000, 3000000),
                        'stars' => fake()->numberBetween(0, 5000),
                        'recent' => fake()->boolean(70),
                    ]])
                    ->all(),
                'frameworks' => fake()->randomElements(['LARAVEL', 'REACT', 'VUE', 'TAILWIND'], fake()->numberBetween(0, 2)),
            ],
            'last_refreshed_at' => now(),
        ];
    }

    public function gold(): static
    {
        return $this->state(fn () => ['ovr' => fake()->numberBetween(90, 99)]);
    }

    public function freeAgent(): static
    {
        return $this->state(fn () => ['nation' => null, 'location' => null]);
    }
}
