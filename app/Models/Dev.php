<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasPublicId;
use Database\Factories\DevFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

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
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'raw_stats' => 'array',
            'last_refreshed_at' => 'datetime',
        ];
    }
}
