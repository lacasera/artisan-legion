<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Jobs\StrikeCardJob;
use Inertia\Inertia;
use Inertia\Response;

class LookupController extends Controller
{
    public function __invoke(string $username): Response
    {
        StrikeCardJob::dispatch($username);

        return Inertia::render('lookup', [
            'username' => $username,
        ]);
    }
}
