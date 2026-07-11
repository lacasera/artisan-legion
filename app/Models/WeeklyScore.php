<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasPublicId;
use Carbon\CarbonInterface;
use Database\Factories\WeeklyScoreFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property CarbonInterface|null $day
 */
class WeeklyScore extends Model
{
    /** @use HasFactory<WeeklyScoreFactory> */
    use HasFactory;

    use HasPublicId;
    use SoftDeletes;

    protected $guarded = [];

    /**
     * @return BelongsTo<Dev, $this>
     */
    public function dev(): BelongsTo
    {
        return $this->belongsTo(Dev::class);
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'day' => 'date',
        ];
    }
}
