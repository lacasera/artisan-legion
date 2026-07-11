<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\CardImageService;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class CardImageController extends Controller
{
    public function __invoke(CardImageService $cardImageService, string $username): BinaryFileResponse
    {
        try {
            $path = $cardImageService->imageFor($username);
        } catch (\Throwable $exception) {
            report($exception);

            $path = public_path('images/og-fallback.png');
        }

        return response()->file($path, [
            'Content-Type' => 'image/png',
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }
}
