<?php

declare(strict_types=1);

namespace App\Services;

use App\Data\DevRatingData;
use App\Data\GitHubProfileData;
use App\Data\LanguageBreakdownData;
use App\Data\LanguageStatData;
use App\Data\RatingBreakdownData;
use Spatie\LaravelData\DataCollection;

class RatingEngine
{
    private const int MAX_STATS = 4;

    /**
     * A real public code footprint is enough to strike a card even after a
     * quiet public year — only accounts with almost no code are unrateable.
     */
    private const int GHOST_MAX_BYTES = 20000;

    private const array BACKEND_LANGUAGES = [
        'PHP', 'Ruby', 'Python', 'Go', 'Java', 'Kotlin', 'C#', 'Elixir', 'Rust', 'C', 'C++', 'Scala', 'Perl', 'Blade',
    ];

    private const array FRONTEND_LANGUAGES = [
        'JavaScript', 'TypeScript', 'CSS', 'HTML', 'Vue', 'Svelte', 'SCSS', 'Astro', 'MDX',
    ];

    private const array INFRA_LANGUAGES = [
        'Shell', 'Dockerfile', 'HCL', 'Makefile', 'Nix', 'PowerShell', 'Batchfile',
    ];

    public function rate(GitHubProfileData $profile): ?DevRatingData
    {
        if ($this->isGhost($profile)) {
            return null;
        }

        $stats = $this->languageStats($profile);

        return new DevRatingData(
            ovr: $this->overall($profile, $stats),
            position: $this->position($profile),
            stats: LanguageStatData::collect($stats, DataCollection::class),
        );
    }

    public function isGhost(GitHubProfileData $profile): bool
    {
        if ($profile->languages === []) {
            return true;
        }

        $totalBytes = array_sum(array_column($profile->languages, 'bytes'));

        return $totalBytes < self::GHOST_MAX_BYTES
            && $profile->totalStars === 0
            && $profile->totalContributions < 10;
    }

    /**
     * Raw volume/impact/recency blend, log-curved and clamped so the top
     * language anchors high and the fourth keeps visual rhythm in the 60s–70s.
     *
     * @return list<LanguageStatData>
     */
    public function languageStats(GitHubProfileData $profile): array
    {
        if ($profile->languages === []) {
            return [];
        }

        $maxBytes = max(1, ...array_column($profile->languages, 'bytes'));
        $maxStars = max(1, ...array_column($profile->languages, 'stars'));

        $rawScores = [];

        foreach ($profile->languages as $name => $aggregate) {
            $rawScores[$name] = 0.55 * $this->logNorm($aggregate['bytes'], $maxBytes)
                + 0.30 * $this->logNorm($aggregate['stars'], $maxStars)
                + 0.15 * ($aggregate['recent'] ? 1.0 : 0.0);
        }

        arsort($rawScores);
        $rawScores = array_slice($rawScores, 0, self::MAX_STATS, preserve_keys: true);

        $anchor = $this->anchor($profile);
        $maxRaw = max(0.0001, ...array_values($rawScores));

        $stats = [];

        foreach ($rawScores as $name => $raw) {
            $stats[] = new LanguageStatData(
                name: mb_strtoupper($name),
                val: (int) min(99, max(40, round($anchor * (0.58 + 0.42 * ($raw / $maxRaw))))),
            );
        }

        return $stats;
    }

    public function position(GitHubProfileData $profile): string
    {
        return $this->positionWithRule($profile)['position'];
    }

    /**
     * @return array{position: string, rule: string}
     */
    public function positionWithRule(GitHubProfileData $profile): array
    {
        if ($profile->totalContributions >= 5000) {
            return ['position' => 'ST', 'rule' => '5,000+ contributions this year — prolific shipper'];
        }

        $shares = $this->categoryShares($profile);

        return match (true) {
            $shares['infra'] >= 0.40 => ['position' => 'GK', 'rule' => '40%+ of code volume is infra — guards the deploy'],
            $shares['backend'] >= 0.65 => ['position' => 'CDM', 'rule' => '65%+ backend volume — anchors the midfield'],
            $shares['frontend'] >= 0.65 => ['position' => 'LW', 'rule' => '65%+ frontend volume — attacks down the wing'],
            $shares['breadth'] >= 4 => ['position' => 'CAM', 'rule' => 'four+ languages each over 10% — breadth playmaker'],
            $shares['backend'] > $shares['frontend'] => ['position' => 'CM', 'rule' => 'backend-leaning mix — runs the engine room'],
            $shares['frontend'] > $shares['backend'] => ['position' => 'RW', 'rule' => 'frontend-leaning mix — width and pace'],
            default => ['position' => 'CB', 'rule' => 'balanced mix — holds the line'],
        };
    }

    public function explain(GitHubProfileData $profile): ?RatingBreakdownData
    {
        if ($this->isGhost($profile)) {
            return null;
        }

        $stats = $this->languageStats($profile);
        $components = $this->overallComponents($profile, $stats);
        $positioned = $this->positionWithRule($profile);
        $totalBytes = max(1, array_sum(array_column($profile->languages, 'bytes')));

        $scoresByName = [];
        foreach ($stats as $stat) {
            $scoresByName[$stat->name] = $stat->val;
        }

        $languages = [];
        foreach ($profile->languages as $name => $aggregate) {
            $label = mb_strtoupper($name);

            if (! isset($scoresByName[$label])) {
                continue;
            }

            $languages[] = new LanguageBreakdownData(
                name: $label,
                score: $scoresByName[$label],
                sharePct: (int) round($aggregate['bytes'] / $totalBytes * 100),
                stars: $aggregate['stars'],
                recent: $aggregate['recent'],
            );
        }

        usort($languages, fn (LanguageBreakdownData $a, LanguageBreakdownData $b) => $b->score <=> $a->score);

        return new RatingBreakdownData(
            contributions: $profile->totalContributions,
            stars: $profile->totalStars,
            followers: $profile->followers,
            activityPct: (int) round($this->activityFactor($profile) * 100),
            position: $positioned['position'],
            positionRule: $positioned['rule'],
            languageBlend: round($components['blend'], 1),
            activityScore: round($components['activity'], 1),
            impactScore: round($components['impact'], 1),
            ovr: $components['ovr'],
            languages: LanguageBreakdownData::collect($languages, DataCollection::class),
        );
    }

    /**
     * @param  list<LanguageStatData>  $stats
     */
    private function overall(GitHubProfileData $profile, array $stats): int
    {
        return $this->overallComponents($profile, $stats)['ovr'];
    }

    /**
     * @param  list<LanguageStatData>  $stats
     * @return array{blend: float, activity: float, impact: float, ovr: int}
     */
    private function overallComponents(GitHubProfileData $profile, array $stats): array
    {
        $weights = array_slice([0.40, 0.25, 0.20, 0.15], 0, count($stats));
        $weightTotal = max(0.0001, array_sum($weights));

        $blend = 0.0;
        foreach ($stats as $index => $stat) {
            $blend += $stat->val * ($weights[$index] / $weightTotal);
        }

        $activity = 40 + 59 * $this->activityFactor($profile);
        $impact = 40 + 59 * min(1.0, $this->logNorm($profile->totalStars + $profile->followers * 3, 50000));

        return [
            'blend' => $blend,
            'activity' => $activity,
            'impact' => $impact,
            'ovr' => (int) min(99, max(40, round(0.75 * $blend + 0.15 * $activity + 0.10 * $impact))),
        ];
    }

    private function anchor(GitHubProfileData $profile): float
    {
        return 55 + 42 * $this->activityFactor($profile);
    }

    /**
     * Followers count alongside stars so org-hosted maintainers (whose
     * repos — and stars — live in the org) are not punished for it.
     */
    private function activityFactor(GitHubProfileData $profile): float
    {
        return min(1.0, $this->logNorm($profile->totalContributions + $profile->totalStars * 2 + $profile->followers, 20000));
    }

    private function logNorm(int|float $value, int|float $max): float
    {
        return log(1 + max(0, $value)) / log(1 + max(1, $max));
    }

    /**
     * @return array{backend: float, frontend: float, infra: float, breadth: int}
     */
    private function categoryShares(GitHubProfileData $profile): array
    {
        $totalBytes = max(1, array_sum(array_column($profile->languages, 'bytes')));
        $backend = $frontend = $infra = 0;
        $breadth = 0;

        foreach ($profile->languages as $name => $aggregate) {
            if ($aggregate['bytes'] / $totalBytes >= 0.10) {
                $breadth++;
            }

            if (in_array($name, self::BACKEND_LANGUAGES, true)) {
                $backend += $aggregate['bytes'];
            } elseif (in_array($name, self::FRONTEND_LANGUAGES, true)) {
                $frontend += $aggregate['bytes'];
            } elseif (in_array($name, self::INFRA_LANGUAGES, true)) {
                $infra += $aggregate['bytes'];
            }
        }

        return [
            'backend' => $backend / $totalBytes,
            'frontend' => $frontend / $totalBytes,
            'infra' => $infra / $totalBytes,
            'breadth' => $breadth,
        ];
    }
}
