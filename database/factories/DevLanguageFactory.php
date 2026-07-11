<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Dev;
use App\Models\DevLanguage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DevLanguage>
 */
class DevLanguageFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'dev_id' => Dev::factory(),
            'language' => fake()->randomElement(['PHP', 'TypeScript', 'JavaScript', 'Python', 'Go', 'Rust']),
            'score' => fake()->numberBetween(40, 99),
            'rank' => fake()->numberBetween(1, 4),
        ];
    }
}
