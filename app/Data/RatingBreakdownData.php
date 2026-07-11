<?php

declare(strict_types=1);

namespace App\Data;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

class RatingBreakdownData extends Data
{
    /**
     * @param  DataCollection<int, LanguageBreakdownData>  $languages
     */
    public function __construct(
        public int $contributions,
        public int $stars,
        public int $followers,
        public int $activityPct,
        public string $position,
        public string $positionRule,
        public float $languageBlend,
        public float $activityScore,
        public float $impactScore,
        public int $ovr,
        #[DataCollectionOf(LanguageBreakdownData::class)]
        public DataCollection $languages,
    ) {}
}
