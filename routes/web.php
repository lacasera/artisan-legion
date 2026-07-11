<?php

declare(strict_types=1);

use App\Http\Controllers\CardImageController;
use App\Http\Controllers\DevCardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LegionController;
use App\Http\Controllers\LookupController;
use App\Http\Controllers\WeeklyWarController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::get('/war', WeeklyWarController::class)->name('war');

Route::get('/lookup/{username}', LookupController::class)
    ->middleware('throttle:lookup')
    ->name('lookup');

Route::get('/cards/{username}', DevCardController::class)
    ->middleware('throttle:cards')
    ->name('cards.show');

Route::get('/cards/{username}/card.png', CardImageController::class)
    ->middleware('throttle:cards')
    ->name('cards.image');

Route::get('/legions', [LegionController::class, 'index'])->name('legions.index');
Route::get('/legions/{code}', [LegionController::class, 'show'])->name('legions.show');
