<?php

declare(strict_types=1);

use App\Jobs\StrikeCardJob;
use Illuminate\Support\Facades\Queue;
use Inertia\Testing\AssertableInertia as Assert;

it('renders_the_lookup_page_with_the_requested_username', function () {
    Queue::fake();

    $this->get(route('lookup', ['username' => 'taylorotwell']))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('lookup')
            ->where('username', 'taylorotwell'));
});

it('dispatches_a_queued_strike_job_to_warm_the_card', function () {
    Queue::fake();

    $this->get(route('lookup', ['username' => 'taylorotwell']))->assertOk();

    Queue::assertPushed(StrikeCardJob::class, fn (StrikeCardJob $job) => $job->username === 'taylorotwell');
});
