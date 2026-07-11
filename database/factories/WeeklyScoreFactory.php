<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Dev;
use App\Models\WeeklyScore;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<WeeklyScore>
 */
class WeeklyScoreFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'dev_id' => Dev::factory(),
            'nation' => fake()->randomElement(['USA', 'GHA', 'FRA', 'IND', 'BRA']),
            'week' => now('UTC')->startOfWeek(CarbonInterface::SUNDAY)->format('Y-m-d'),
            'points' => fake()->numberBetween(0, 5000),
            'day' => now('UTC')->toDateString(),
            'day_commits' => fake()->numberBetween(0, 30),
            'day_points' => fake()->numberBetween(0, 800),
        ];
    }
}
