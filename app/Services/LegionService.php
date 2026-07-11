<?php

declare(strict_types=1);

namespace App\Services;

use App\Data\LegionCaptainData;
use App\Data\LegionData;
use App\Data\LegionPlayerData;
use App\Data\LegionSummaryData;
use App\Models\Dev;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Spatie\LaravelData\DataCollection;

class LegionService
{
    public const string WORLD_CODE = 'WLD';

    private const int XI_SIZE = 11;

    private const int RESERVE_SIZE = 6;

    private const array ROW_CAPACITIES = [
        'attack' => 3,
        'midfield' => 3,
        'defense' => 4,
        'goalkeeper' => 1,
    ];

    private const array OVERFLOW_ORDER = ['defense', 'midfield', 'attack', 'goalkeeper'];

    public function legionFor(string $code): ?LegionData
    {
        $devs = $this->devsQuery($code)
            ->with('languages')
            ->orderByDesc('ovr')
            ->orderBy('id')
            ->get();

        if ($devs->isEmpty()) {
            return null;
        }

        $xi = $devs->take(self::XI_SIZE)->values();
        $captain = $xi->first();
        $rows = $this->arrangeFormation($xi, $captain);

        return new LegionData(
            code: $code,
            rank: $this->rankOf($code),
            soldierCount: $devs->count(),
            averageOvr: number_format((float) $xi->avg('ovr'), 1),
            attack: $rows['attack'],
            midfield: $rows['midfield'],
            defense: $rows['defense'],
            goalkeeper: $rows['goalkeeper'],
            captain: LegionCaptainData::fromDev($captain),
            reserves: LegionPlayerData::collect(
                $devs->slice(self::XI_SIZE, self::RESERVE_SIZE)->values()->map(
                    fn (Dev $dev) => LegionPlayerData::fromDev($dev),
                ),
                DataCollection::class,
            ),
            recentEnlistments: $this->devsQuery($code)
                ->where('created_at', '>=', now()->subDay())
                ->count(),
        );
    }

    /**
     * @return DataCollection<int, LegionSummaryData>
     */
    public function legionsIndex(): DataCollection
    {
        $summaries = Dev::query()
            ->orderByDesc('ovr')
            ->orderBy('id')
            ->get(['nation', 'ovr', 'username'])
            ->groupBy(fn (Dev $dev) => $dev->nation ?? self::WORLD_CODE)
            ->map(fn (Collection $group, string $code) => new LegionSummaryData(
                code: $code,
                soldiers: $group->count(),
                averageOvr: number_format((float) $group->take(self::XI_SIZE)->avg('ovr'), 1),
                topSoldier: $group->first()->username,
            ))
            ->sortByDesc(fn (LegionSummaryData $summary) => (float) $summary->averageOvr)
            ->values();

        return LegionSummaryData::collect($summaries, DataCollection::class);
    }

    private function rankOf(string $code): int
    {
        $position = $this->legionsIndex()
            ->toCollection()
            ->search(fn (LegionSummaryData $summary) => $summary->code === $code);

        return $position === false ? 0 : $position + 1;
    }

    /**
     * Greedy 4–3–3 slotting: each dev lands in their natural row while it has
     * space, otherwise the first open row (defense first — someone has to).
     *
     * @param  Collection<int, Dev>  $xi
     * @return array{attack: DataCollection<int, LegionPlayerData>, midfield: DataCollection<int, LegionPlayerData>, defense: DataCollection<int, LegionPlayerData>, goalkeeper: DataCollection<int, LegionPlayerData>}
     */
    private function arrangeFormation(Collection $xi, Dev $captain): array
    {
        $rows = ['attack' => [], 'midfield' => [], 'defense' => [], 'goalkeeper' => []];

        foreach ($xi as $dev) {
            $row = $this->naturalRow($dev->position);

            if (count($rows[$row]) >= self::ROW_CAPACITIES[$row]) {
                foreach (self::OVERFLOW_ORDER as $fallback) {
                    if (count($rows[$fallback]) < self::ROW_CAPACITIES[$fallback]) {
                        $row = $fallback;
                        break;
                    }
                }
            }

            $rows[$row][] = LegionPlayerData::fromDev($dev, captain: $dev->is($captain));
        }

        return [
            'attack' => LegionPlayerData::collect($rows['attack'], DataCollection::class),
            'midfield' => LegionPlayerData::collect($rows['midfield'], DataCollection::class),
            'defense' => LegionPlayerData::collect($rows['defense'], DataCollection::class),
            'goalkeeper' => LegionPlayerData::collect($rows['goalkeeper'], DataCollection::class),
        ];
    }

    private function naturalRow(string $position): string
    {
        return match ($position) {
            'ST', 'LW', 'RW' => 'attack',
            'CAM', 'CDM', 'CM' => 'midfield',
            'CB', 'LB', 'RB' => 'defense',
            'GK' => 'goalkeeper',
            default => 'midfield',
        };
    }

    /**
     * @return Builder<Dev>
     */
    private function devsQuery(string $code): Builder
    {
        return Dev::query()->when(
            $code === self::WORLD_CODE,
            fn (Builder $query) => $query->whereNull('nation'),
            fn (Builder $query) => $query->where('nation', $code),
        );
    }
}
