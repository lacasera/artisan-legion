<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\DevCardService;
use Inertia\Inertia;
use Inertia\Response;

class DevCardController extends Controller
{
    public function __invoke(DevCardService $devCardService, string $username): Response
    {
        $card = $devCardService->cardFor($username);

        return Inertia::render('cards/show', [
            'username' => $username,
            'dev' => $card,
            'og' => [
                'title' => $card !== null
                    ? "@{$card->handle} · OVR {$card->ovr} · Artisan Legion"
                    : "@{$username} · Artisan Legion",
                'description' => "See @{$username}'s rated card and join the weekly commit war.",
                'image' => route('cards.image', ['username' => $username]),
            ],
        ]);
    }
}
