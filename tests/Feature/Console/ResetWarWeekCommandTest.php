<?php

declare(strict_types=1);

use App\Models\Dev;
use App\Models\WeeklyScore;
use App\Services\WeeklyWarService;

it('retires_scores_from_finished_weeks_and_keeps_the_current_one', function () {
    $dev = Dev::factory()->create();
    $current = WeeklyScore::factory()->create([
        'dev_id' => $dev->id,
        'week' => app(WeeklyWarService::class)->weekKey(),
    ]);
    $finished = WeeklyScore::factory()->create([
        'dev_id' => Dev::factory()->create()->id,
        'week' => '2020-01-05',
    ]);

    $this->artisan('war:reset')->assertSuccessful();

    expect(WeeklyScore::query()->pluck('id')->all())->toBe([$current->id])
        ->and(WeeklyScore::withTrashed()->find($finished->id)->trashed())->toBeTrue();
});
