<?php

declare(strict_types=1);

use App\Services\CardImageService;

it('streams_the_rendered_card_png', function () {
    $path = sys_get_temp_dir().'/artisan-legion-card-test.png';
    file_put_contents($path, 'png-bytes');

    $this->mock(CardImageService::class)
        ->shouldReceive('imageFor')
        ->once()
        ->with('taylorotwell')
        ->andReturn($path);

    $this->get(route('cards.image', ['username' => 'taylorotwell']))
        ->assertOk()
        ->assertHeader('Content-Type', 'image/png');
});

it('serves_the_fallback_banner_when_rendering_fails', function () {
    $this->mock(CardImageService::class)
        ->shouldReceive('imageFor')
        ->once()
        ->andThrow(new RuntimeException('chrome exploded'));

    $this->get(route('cards.image', ['username' => 'taylorotwell']))
        ->assertOk()
        ->assertHeader('Content-Type', 'image/png');
});
