<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\WeeklyWarService;
use Inertia\Inertia;
use Inertia\Response;

class WeeklyWarController extends Controller
{
    public function __invoke(WeeklyWarService $weeklyWarService): Response
    {
        return Inertia::render('war', [
            'board' => $weeklyWarService->board(),
            'pushingCount' => $weeklyWarService->pushingCount(),
            'resetsAt' => $weeklyWarService->resetsAt()->toIso8601String(),
        ]);
    }
}
