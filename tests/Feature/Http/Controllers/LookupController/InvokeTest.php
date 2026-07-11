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

it('rejects_invalid_github_usernames_before_dispatching_anything', function (string $username) {
    Queue::fake();

    $this->get("/lookup/{$username}")->assertNotFound();

    Queue::assertNothingPushed();
})->with([
    'leading hyphen' => '-taylor',
    'trailing hyphen' => 'taylor-',
    'double hyphen' => 'tay--lor',
    'too long' => str_repeat('a', 40),
]);

it('rate_limits_repeated_lookups_per_ip', function () {
    Queue::fake();

    foreach (range(1, 10) as $attempt) {
        $this->get(route('lookup', ['username' => "dev{$attempt}"]))->assertOk();
    }

    $this->get(route('lookup', ['username' => 'dev11']))->assertStatus(429);
});
