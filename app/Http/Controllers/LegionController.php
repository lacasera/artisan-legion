<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

class LegionController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('legions/index');
    }

    public function show(string $code): Response
    {
        return Inertia::render('legions/show', [
            'code' => strtoupper($code),
        ]);
    }
}
