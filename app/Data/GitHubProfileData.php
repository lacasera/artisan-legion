<?php

declare(strict_types=1);

namespace App\Data;

use Spatie\LaravelData\Data;

class GitHubProfileData extends Data
{
    /**
     * @param  array<string, array{bytes: int, stars: int, recent: bool}>  $languages
     * @param  list<string>  $frameworks
     */
    public function __construct(
        public string $login,
        public ?string $name,
        public ?string $avatarUrl,
        public ?string $location,
        public int $followers,
        public int $totalContributions,
        public int $totalStars,
        public array $languages,
        public array $frameworks = [],
    ) {}

    /**
     * Rebuild a profile from a dev's persisted raw_stats — no API call.
     * Null when the stored stats predate the raw_stats shape.
     *
     * @param  array<string, mixed>  $rawStats
     */
    public static function fromRawStats(string $login, array $rawStats): ?self
    {
        $languages = $rawStats['languages'] ?? [];

        if (! is_array($languages) || $languages === []) {
            return null;
        }

        return new self(
            login: $login,
            name: null,
            avatarUrl: null,
            location: null,
            followers: (int) ($rawStats['followers'] ?? 0),
            totalContributions: (int) ($rawStats['contributions'] ?? 0),
            totalStars: (int) ($rawStats['stars'] ?? 0),
            languages: $languages,
            frameworks: array_values((array) ($rawStats['frameworks'] ?? [])),
        );
    }
}
