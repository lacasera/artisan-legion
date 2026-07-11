<?php

declare(strict_types=1);

namespace App\Services;

use App\Data\DevCardData;
use App\Data\DevRatingData;
use App\Data\GitHubProfileData;
use App\Models\Dev;
use App\Services\GitHub\GitHubClient;
use Illuminate\Support\Facades\Cache;

class DevCardService
{
    public function __construct(
        private GitHubClient $github,
        private RatingEngine $ratingEngine,
        private CountryResolver $countryResolver,
    ) {}

    /**
     * Cached daily per username; misses (unknown user / ghost / rate limit)
     * are cached for an hour so we back off instead of hammering the API.
     */
    public function cardFor(string $username): ?DevCardData
    {
        $key = 'dev-card:'.mb_strtolower($username);

        /** @var array{card: array<string, mixed>|null}|null $payload */
        $payload = Cache::get($key);

        if ($payload === null) {
            $card = $this->strike($username);
            $payload = ['card' => $card?->toArray()];
            Cache::put($key, $payload, $card === null ? now()->addHour() : now()->addDay());
        }

        return $payload['card'] === null ? null : DevCardData::from($payload['card']);
    }

    private function strike(string $username): ?DevCardData
    {
        try {
            $profile = $this->github->fetchProfile($username);
        } catch (\Throwable $exception) {
            report($exception);

            return $this->fromPersisted($username);
        }

        if ($profile === null) {
            return null;
        }

        $rating = $this->ratingEngine->rate($profile);

        if ($rating === null) {
            return null;
        }

        $dev = Dev::query()->updateOrCreate(
            ['username' => mb_strtolower($profile->login)],
            [
                'name' => $profile->name,
                'avatar_url' => $profile->avatarUrl,
                'location' => $profile->location,
                'nation' => $this->countryResolver->resolve($profile->location),
                'ovr' => $rating->ovr,
                'position' => $rating->position,
                'raw_stats' => [
                    'followers' => $profile->followers,
                    'contributions' => $profile->totalContributions,
                    'stars' => $profile->totalStars,
                    'languages' => $profile->languages,
                ],
                'last_refreshed_at' => now(),
            ],
        );

        $dev->languages()->delete();
        $dev->languages()->createMany(
            collect($rating->stats->toCollection())->values()->map(fn ($stat, int $index) => [
                'language' => $stat->name,
                'score' => $stat->val,
                'rank' => $index + 1,
            ])->all(),
        );

        return DevCardData::fromDev($dev->load('languages'), $this->rankLabelFor($dev));
    }

    private function fromPersisted(string $username): ?DevCardData
    {
        $dev = Dev::query()->where('username', mb_strtolower($username))->first();

        if ($dev === null) {
            return null;
        }

        return DevCardData::fromDev($dev->load('languages'), $this->rankLabelFor($dev));
    }

    private function rankLabelFor(Dev $dev): string
    {
        if ($dev->nation === null) {
            $rank = Dev::query()->where('ovr', '>', $dev->ovr)->count() + 1;

            return 'GLOBAL #'.number_format($rank);
        }

        $rank = Dev::query()
            ->where('nation', $dev->nation)
            ->where('ovr', '>', $dev->ovr)
            ->count() + 1;

        return $dev->nation.' · NAT #'.$rank;
    }

    /**
     * Exposed for tests tuning the rating curve against captured profiles.
     */
    public function rate(GitHubProfileData $profile): ?DevRatingData
    {
        return $this->ratingEngine->rate($profile);
    }
}
