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
     * Devs per aliased GraphQL request — one request per chunk, each ~1
     * rate-limit point. Well under GitHub's ~10s query timeout.
     */
    private const int CHUNK_SIZE = 500;

    /**
     * Seconds between chunk dispatches — spreads worker load across the window.
     */
    private const int STAGGER_SECONDS = 5;

    public function handle(): int
    {
        $devIds = Dev::query()->orderBy('id')->pluck('id')->map(fn ($id): int => (int) $id)->all();
        $chunks = array_chunk($devIds, self::CHUNK_SIZE);

        foreach ($chunks as $index => $chunk) {
            PollDevCommitsJob::dispatch($chunk)
                ->delay(now()->addSeconds($index * self::STAGGER_SECONDS));
        }

        $this->info('Dispatched '.count($chunks).' polling jobs for '.count($devIds).' devs.');

        return self::SUCCESS;
    }
}
