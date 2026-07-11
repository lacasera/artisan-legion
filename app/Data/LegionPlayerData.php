<?php

declare(strict_types=1);

namespace App\Data;

use App\Models\Dev;
use Spatie\LaravelData\Data;

class LegionPlayerData extends Data
{
    public function __construct(
        public string $id,
        public string $handle,
        public int $ovr,
        public string $pos,
        public string $topLanguage,
        public bool $captain,
    ) {}

    public static function fromDev(Dev $dev, bool $captain = false): self
    {
        return new self(
            id: $dev->public_id,
            handle: $dev->username,
            ovr: $dev->ovr,
            pos: $dev->position,
            topLanguage: mb_strtoupper($dev->languages->first()->language ?? '—'),
            captain: $captain,
        );
    }
}
