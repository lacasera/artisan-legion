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
}
