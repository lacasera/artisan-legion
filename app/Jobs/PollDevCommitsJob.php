<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Dev;
use App\Services\GitHub\GitHubClient;
use App\Services\GitHub\GitHubRateLimitedException;
use App\Services\WeeklyWarService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class PollDevCommitsJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 1;

    /**
     * @param  list<int>  $devIds
     */
    public function __construct(public array $devIds) {}

    public function handle(GitHubClient $github, WeeklyWarService $weeklyWarService): void
    {
        $devs = Dev::query()->whereIn('id', $this->devIds)->get()->keyBy('username');

        if ($devs->isEmpty()) {
            return;
        }

        try {
            $counts = $github->fetchContributionCounts(array_values($devs->keys()->all()));
        } catch (GitHubRateLimitedException $exception) {
            report($exception);

            return;
        }

        foreach ($counts as $login => $current) {
            /** @var Dev|null $dev */
            $dev = $devs->get($login);

            if ($dev === null) {
                continue;
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
}
