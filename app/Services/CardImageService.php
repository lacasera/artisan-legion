<?php

declare(strict_types=1);

namespace App\Services;

use Spatie\Browsershot\Browsershot;

class CardImageService
{
    /**
     * Render (or reuse a cached) share PNG of a dev's card page.
     */
    public function imageFor(string $username): string
    {
        $path = storage_path('app/cards/'.mb_strtolower($username).'.png');

        if (file_exists($path) && filemtime($path) > now()->subDay()->getTimestamp()) {
            return $path;
        }

        if (! is_dir(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }

        $browsershot = Browsershot::url(route('cards.show', ['username' => $username]))
            ->windowSize(1280, 900)
            ->deviceScaleFactor(2)
            ->waitUntilNetworkIdle()
            ->select('[data-card-frame]');

        if (config('services.browsershot.no_sandbox')) {
            $browsershot->noSandbox();
        }

        $browsershot->save($path);

        return $path;
    }
}
