<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Services\DevCardService;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class StrikeCardJob implements ShouldBeUnique, ShouldQueue
{
    use Queueable;

    public int $tries = 2;

    public int $uniqueFor = 300;

    public function __construct(public string $username) {}

    public function uniqueId(): string
    {
        return mb_strtolower($this->username);
    }

    public function handle(DevCardService $devCardService): void
    {
        $devCardService->cardFor($this->username);
    }
}
