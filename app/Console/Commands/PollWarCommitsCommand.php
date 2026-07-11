<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Jobs\PollDevCommitsJob;
use App\Models\Dev;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('war:poll')]
#[Description('Dispatch staggered queued jobs polling every rostered dev for new commits')]
class PollWarCommitsCommand extends Command
{
    /**
     * Seconds between job dispatches — spreads the GraphQL budget across the
     * scheduler window instead of bursting it.
     */
    private const int STAGGER_SECONDS = 2;

    public function handle(): int
    {
        $dispatched = 0;

        Dev::query()
            ->orderBy('id')
            ->pluck('id')
            ->each(function (int $devId, int $index) use (&$dispatched) {
                PollDevCommitsJob::dispatch($devId)->delay(now()->addSeconds($index * self::STAGGER_SECONDS));
                $dispatched++;
            });

        $this->info("Dispatched {$dispatched} polling jobs.");

        return self::SUCCESS;
    }
}
