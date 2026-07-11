<?php

declare(strict_types=1);

use App\Jobs\PollDevCommitsJob;
use App\Models\Dev;
use Illuminate\Support\Facades\Queue;

it('dispatches_a_polling_job_per_dev', function () {
    Queue::fake();
    Dev::factory()->count(3)->create();

    $this->artisan('war:poll')->assertSuccessful();

    Queue::assertPushed(PollDevCommitsJob::class, 3);
});

it('dispatches_nothing_when_no_devs_exist', function () {
    Queue::fake();

    $this->artisan('war:poll')->assertSuccessful();

    Queue::assertNothingPushed();
});
