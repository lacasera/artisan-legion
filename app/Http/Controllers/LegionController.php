<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\LegionService;
use Inertia\Inertia;
use Inertia\Response;

class LegionController extends Controller
{
    public function __construct(private LegionService $legionService) {}

    public function index(): Response
    {
        return Inertia::render('legions/index', [
            'legions' => $this->legionService->legionsIndex(),
        ]);
    }

    public function show(string $code): Response
    {
        $legion = $this->legionService->legionFor(strtoupper($code));

        abort_if($legion === null, 404);

        return Inertia::render('legions/show', [
            'legion' => $legion,
        ]);
    }
}
