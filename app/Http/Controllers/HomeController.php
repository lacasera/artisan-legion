<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Dev;
use App\Services\WeeklyWarService;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function __invoke(WeeklyWarService $weeklyWarService): Response
    {
        return Inertia::render('home', [
            'ticker' => $weeklyWarService->board(),
            'soldierCount' => Dev::query()->count(),
        ]);
    }
}
