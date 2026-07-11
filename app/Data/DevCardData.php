<?php

declare(strict_types=1);

namespace App\Data;

use App\Models\Dev;
use App\Models\DevLanguage;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

class DevCardData extends Data
{
    /**
     * @param  DataCollection<int, LanguageStatData>  $stats
     * @param  list<string>  $frameworks
     */
    public function __construct(
        public string $id,
        public string $name,
        public string $handle,
        public ?string $avatar,
        public int $ovr,
        public string $pos,
        public ?string $nation,
        public string $rankLabel,
        public string $serial,
        public bool $specialist,
        #[DataCollectionOf(LanguageStatData::class)]
        public DataCollection $stats,
        public array $frameworks = [],
    ) {}

    public static function fromDev(Dev $dev, string $rankLabel): self
    {
        return new self(
            id: $dev->public_id,
            name: mb_strtoupper($dev->name ?? $dev->username),
            handle: $dev->username,
            avatar: $dev->avatar_url,
            ovr: $dev->ovr,
            pos: $dev->position,
            nation: $dev->nation,
            rankLabel: $rankLabel,
            serial: str_pad((string) $dev->id, 5, '0', STR_PAD_LEFT),
            specialist: $dev->languages->count() === 1,
            stats: LanguageStatData::collect(
                $dev->languages->map(fn (DevLanguage $language) => new LanguageStatData(
                    name: mb_strtoupper($language->language),
                    val: $language->score,
                )),
                DataCollection::class,
            ),
            frameworks: array_values((array) data_get($dev->raw_stats, 'frameworks', [])),
        );
    }
}
