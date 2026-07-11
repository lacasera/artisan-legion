<?php

declare(strict_types=1);

namespace App\Providers;

use App\Services\GitHub\GitHubClient;
use App\Services\GitHub\GraphQLGitHubClient;
use Carbon\CarbonImmutable;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(GitHubClient::class, GraphQLGitHubClient::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaults();
        $this->configureRouting();
    }

    /**
     * Malformed usernames 404 before reaching a controller, and the public
     * GitHub-budget-burning endpoints get per-IP limits.
     */
    protected function configureRouting(): void
    {
        Route::pattern('username', '[a-zA-Z0-9](?:[a-zA-Z0-9]|-(?=[a-zA-Z0-9])){0,38}');
        Route::pattern('code', '[A-Za-z]{3}');

        RateLimiter::for('lookup', fn (Request $request) => Limit::perMinute(10)->by($request->ip()));
        RateLimiter::for('cards', fn (Request $request) => Limit::perMinute(30)->by($request->ip()));
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );
    }
}
