<?php

declare(strict_types=1);

namespace App\Data;

use Spatie\LaravelData\Data;

class LegionSummaryData extends Data
{
    public function __construct(
        public string $code,
        public int $soldiers,
        public string $averageOvr,
        public string $topSoldier,
    ) {}
}
