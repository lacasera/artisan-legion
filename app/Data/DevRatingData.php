<?php

declare(strict_types=1);

namespace App\Data;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

class DevRatingData extends Data
{
    /**
     * @param  DataCollection<int, LanguageStatData>  $stats
     */
    public function __construct(
        public int $ovr,
        public string $position,
        #[DataCollectionOf(LanguageStatData::class)]
        public DataCollection $stats,
    ) {}
}
