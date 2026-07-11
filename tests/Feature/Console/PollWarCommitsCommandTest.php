<?php

declare(strict_types=1);

use App\Jobs\PollDevCommitsJob;
use App\Models\Dev;
use Illuminate\Support\Facades\Queue;

it('dispatches_one_batched_job_carrying_every_dev_in_the_chunk', function () {
    Queue::fake();
    $devs = Dev::factory()->count(3)->create();

    $this->artisan('war:poll')->assertSuccessful();

    Queue::assertPushed(PollDevCommitsJob::class, 1);
    Queue::assertPushed(
        PollDevCommitsJob::class,
        fn (PollDevCommitsJob $job) => $job->devIds === $devs->pluck('id')->all(),
    );
});

it('dispatches_nothing_when_no_devs_exist', function () {
    Queue::fake();

    $this->artisan('war:poll')->assertSuccessful();

    Queue::assertNothingPushed();
});
