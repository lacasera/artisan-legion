<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Dev;
use App\Services\GitHub\GitHubClient;
use App\Services\GitHub\GitHubRateLimitedException;
use App\Services\WeeklyWarService;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class PollDevCommitsJob implements ShouldBeUnique, ShouldQueue
{
    use Queueable;

    public int $tries = 1;

    public int $uniqueFor = 240;

    public function __construct(public int $devId) {}

    public function uniqueId(): string
    {
        return (string) $this->devId;
    }

    public function handle(GitHubClient $github, WeeklyWarService $weeklyWarService): void
    {
        $dev = Dev::query()->find($this->devId);

        if ($dev === null) {
            return;
        }

        try {
            $current = $github->fetchContributionCount($dev->username);
        } catch (GitHubRateLimitedException $exception) {
            report($exception);

            return;
        }

        if ($current === null) {
            return;
        }

        $baseline = $dev->last_contribution_count;
        $delta = $baseline === null ? 0 : max(0, $current - $baseline);

        $dev->last_contribution_count = max(0, $current);
        $dev->last_polled_at = now();

        if ($delta > 0) {
            $dev->last_active_at = now();
            $weeklyWarService->awardCommits($dev, $delta);
        }

        $dev->save();
    }
}
