<?php

declare(strict_types=1);

namespace App\Data;

use Spatie\LaravelData\Data;

class LanguageStatData extends Data
{
    public function __construct(
        public string $name,
        public int $val,
    ) {}
}
