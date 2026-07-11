<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\WeeklyScore;
use App\Services\WeeklyWarService;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('war:reset')]
#[Description('Retire finished war weeks — ranks are wiped, cards remain')]
class ResetWarWeekCommand extends Command
{
    public function handle(WeeklyWarService $weeklyWarService): int
    {
        $retired = WeeklyScore::query()
            ->where('week', '!=', $weeklyWarService->weekKey())
            ->delete();

        $this->info("Retired {$retired} score rows from finished weeks.");

        return self::SUCCESS;
    }
}
