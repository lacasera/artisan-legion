<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\CardImageService;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class CardImageController extends Controller
{
    public function __invoke(CardImageService $cardImageService, string $username): BinaryFileResponse
    {
        return response()->file($cardImageService->imageFor($username), [
            'Content-Type' => 'image/png',
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }
}
