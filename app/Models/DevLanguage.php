<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasPublicId;
use Database\Factories\DevLanguageFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class DevLanguage extends Model
{
    /** @use HasFactory<DevLanguageFactory> */
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
}
