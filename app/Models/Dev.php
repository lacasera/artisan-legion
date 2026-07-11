<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasPublicId;
use Carbon\CarbonInterface;
use Database\Factories\DevFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int|null $last_contribution_count
 * @property CarbonInterface|null $last_polled_at
 * @property CarbonInterface|null $last_active_at
 */
class Dev extends Model
{
    /** @use HasFactory<DevFactory> */
    use HasFactory;

    use HasPublicId;
    use SoftDeletes;

    protected $guarded = [];

    /**
     * @return HasMany<DevLanguage, $this>
     */
    public function languages(): HasMany
    {
        return $this->hasMany(DevLanguage::class)->orderBy('rank');
    }

    /**
     * @return HasMany<WeeklyScore, $this>
     */
    public function weeklyScores(): HasMany
    {
        return $this->hasMany(WeeklyScore::class);
    }

    public function initials(): string
    {
        $name = mb_strtoupper($this->name ?? $this->username);

        return collect(explode(' ', $name))
            ->map(fn (string $word) => mb_substr($word, 0, 1))
            ->take(2)
            ->implode('');
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'raw_stats' => 'array',
            'last_refreshed_at' => 'datetime',
            'last_polled_at' => 'datetime',
            'last_active_at' => 'datetime',
        ];
    }
}
