<?php

declare(strict_types=1);

namespace App\Data;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

class LegionData extends Data
{
    /**
     * @param  DataCollection<int, LegionPlayerData>  $attack
     * @param  DataCollection<int, LegionPlayerData>  $midfield
     * @param  DataCollection<int, LegionPlayerData>  $defense
     * @param  DataCollection<int, LegionPlayerData>  $goalkeeper
     * @param  DataCollection<int, LegionPlayerData>  $reserves
     */
    public function __construct(
        public string $code,
        public int $rank,
        public int $soldierCount,
        public string $averageOvr,
        #[DataCollectionOf(LegionPlayerData::class)]
        public DataCollection $attack,
        #[DataCollectionOf(LegionPlayerData::class)]
        public DataCollection $midfield,
        #[DataCollectionOf(LegionPlayerData::class)]
        public DataCollection $defense,
        #[DataCollectionOf(LegionPlayerData::class)]
        public DataCollection $goalkeeper,
        public ?LegionCaptainData $captain,
        #[DataCollectionOf(LegionPlayerData::class)]
        public DataCollection $reserves,
        public int $recentEnlistments,
    ) {}
}
