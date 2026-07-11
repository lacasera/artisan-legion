<?php

declare(strict_types=1);

namespace App\Services;

use App\Data\WarBoardEntryData;
use App\Models\Dev;
use App\Models\WeeklyScore;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Spatie\LaravelData\DataCollection;

class WeeklyWarService
{
    private const int POINTS_PER_COMMIT = 10;

    private const int FULL_VALUE_DAILY_COMMITS = 30;

    private const float DIMINISHED_RATE = 0.25;

    private const int DAILY_COMMIT_CEILING = 100;

    private const int BOARD_CACHE_SECONDS = 5;

    public function weekKey(): string
    {
        return now('UTC')->startOfWeek(CarbonInterface::SUNDAY)->format('Y-m-d');
    }

    public function weekLabel(): string
    {
        return 'W'.now('UTC')->startOfWeek(CarbonInterface::SUNDAY)->isoWeek();
    }

    public function resetsAt(): CarbonImmutable
    {
        return CarbonImmutable::parse($this->weekKey(), 'UTC')->addWeek();
    }

    /**
     * Award war points for a commit delta. Anti-cheese: the first 30 commits
     * of a day are worth full value, 31–100 a quarter, beyond 100 nothing —
     * and every point is weighted by the dev's OVR (a 90 outweighs a 45 2:1).
     */
    public function awardCommits(Dev $dev, int $commits): int
    {
        if ($commits <= 0) {
            return 0;
        }

        $score = WeeklyScore::query()->firstOrCreate(
            ['dev_id' => $dev->id, 'week' => $this->weekKey()],
            ['nation' => $dev->nation, 'day' => now('UTC')->toDateString()],
        );

        $today = now('UTC')->toDateString();

        if ($score->day?->toDateString() !== $today) {
            $score->day = now('UTC')->startOfDay();
            $score->day_commits = 0;
            $score->day_points = 0;
        }

        $before = $score->day_commits;
        $end = $before + $commits;
        $fullUnits = max(0, min($end, self::FULL_VALUE_DAILY_COMMITS) - $before);
        $diminishedUnits = max(0, min($end, self::DAILY_COMMIT_CEILING) - max($before, self::FULL_VALUE_DAILY_COMMITS));
        $ovrWeight = $dev->ovr / 50;

        $awarded = max(0, (int) round(self::POINTS_PER_COMMIT * $ovrWeight * ($fullUnits + $diminishedUnits * self::DIMINISHED_RATE)));

        $score->nation = $dev->nation;
        $score->points += $awarded;
        $score->day_commits += $commits;
        $score->day_points += $awarded;
        $score->save();

        return $awarded;
    }

    /**
     * @return DataCollection<int, WarBoardEntryData>
     */
    public function board(): DataCollection
    {
        $entries = Cache::remember(
            'war-board:'.$this->weekKey(),
            self::BOARD_CACHE_SECONDS,
            fn (): array => $this->buildBoard(),
        );

        return WarBoardEntryData::collect($entries, DataCollection::class);
    }

    public function pushingCount(): int
    {
        return Dev::query()->where('last_active_at', '>=', now()->subHour())->count();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function buildBoard(): array
    {
        $today = now('UTC')->toDateString();

        $scores = WeeklyScore::query()
            ->where('week', $this->weekKey())
            ->with('dev:id,username,nation,last_active_at')
            ->get();

        if ($scores->isEmpty()) {
            return [];
        }

        $legions = $scores
            ->groupBy(fn (WeeklyScore $score) => $score->nation ?? LegionService::WORLD_CODE)
            ->map(function (Collection $group, string $code) use ($today) {
                $topScore = $group->sortByDesc('points')->first();

                return [
                    'code' => $code,
                    'points' => (int) $group->sum('points'),
                    'dayPoints' => (int) $group->where(fn (WeeklyScore $score) => $score->day?->toDateString() === $today)->sum('day_points'),
                    'topSoldier' => $topScore->dev->username ?? '—',
                    'pushing' => $group->contains(
                        fn (WeeklyScore $score) => $score->dev !== null
                            && $score->dev->last_active_at !== null
                            && $score->dev->last_active_at->gte(now()->subHour()),
                    ),
                ];
            })
            ->values();

        $ranked = $legions->sortByDesc('points')->values();
        $dayStartRanks = $legions
            ->sortByDesc(fn (array $legion) => $legion['points'] - $legion['dayPoints'])
            ->values()
            ->mapWithKeys(fn (array $legion, int $index) => [$legion['code'] => $index + 1]);

        $maxPoints = max(1, (int) $ranked->first()['points']);

        return $ranked
            ->map(function (array $legion, int $index) use ($dayStartRanks, $maxPoints) {
                $rank = $index + 1;
                $startRank = $dayStartRanks[$legion['code']] ?? $rank;
                $delta = $startRank - $rank;

                return [
                    ...$legion,
                    'rank' => $rank,
                    'move' => $delta > 0 ? "▲{$delta}" : ($delta < 0 ? '▼'.abs($delta) : '—'),
                    'pct' => (int) round($legion['points'] / $maxPoints * 100),
                ];
            })
            ->values()
            ->all();
    }
}
