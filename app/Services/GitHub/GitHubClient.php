<?php

declare(strict_types=1);

namespace App\Services\GitHub;

use App\Data\GitHubProfileData;

interface GitHubClient
{
    /**
     * Fetch aggregated public stats for a username, or null when the user does not exist.
     */
    public function fetchProfile(string $username): ?GitHubProfileData;
}
