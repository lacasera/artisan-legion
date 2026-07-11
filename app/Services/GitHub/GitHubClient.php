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

    /**
     * Fetch past-year contribution totals for many logins in a single aliased
     * request — the weekly-war polling loop. Missing/renamed users are omitted.
     *
     * @param  list<string>  $logins
     * @return array<string, int> input login => contribution count
     */
    public function fetchContributionCounts(array $logins): array;
}
