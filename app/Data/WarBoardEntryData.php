<?php

declare(strict_types=1);

namespace App\Data;

use Spatie\LaravelData\Data;

class WarBoardEntryData extends Data
{
    public function __construct(
        public string $code,
        public int $rank,
        public string $move,
        public int $points,
        public int $dayPoints,
        public string $topSoldier,
        public bool $pushing,
        public int $pct,
    ) {}
}
