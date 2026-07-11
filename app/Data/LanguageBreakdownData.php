<?php

declare(strict_types=1);

namespace App\Data;

use Spatie\LaravelData\Data;

class LanguageBreakdownData extends Data
{
    public function __construct(
        public string $name,
        public int $score,
        public int $sharePct,
        public int $stars,
        public bool $recent,
    ) {}
}
