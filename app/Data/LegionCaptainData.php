<?php

declare(strict_types=1);

namespace App\Data;

use App\Models\Dev;
use Spatie\LaravelData\Data;

class LegionCaptainData extends Data
{
    public function __construct(
        public string $id,
        public string $name,
        public string $handle,
        public string $pos,
        public int $ovr,
        public string $initials,
    ) {}

    public static function fromDev(Dev $dev): self
    {
        $name = mb_strtoupper($dev->name ?? $dev->username);

        return new self(
            id: $dev->public_id,
            name: $name,
            handle: $dev->username,
            pos: $dev->position,
            ovr: $dev->ovr,
            initials: $dev->initials(),
        );
    }
}
