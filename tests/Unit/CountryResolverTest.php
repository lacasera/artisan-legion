<?php

declare(strict_types=1);

use App\Services\CountryResolver;

it('resolves_locations_to_nation_codes', function (?string $location, ?string $expected) {
    expect((new CountryResolver)->resolve($location))->toBe($expected);
})->with([
    'city with US state code' => ['Little Rock, AR', 'USA'],
    'US state code with period' => ['Austin, TX.', 'USA'],
    'country name' => ['Accra, Ghana', 'GHA'],
    'city only' => ['Accra', 'GHA'],
    'accented city' => ['São Paulo', 'BRA'],
    'longest needle wins' => ['New Delhi, India', 'IND'],
    'belgium' => ['Antwerp, Belgium', 'BEL'],
    'ireland' => ['Dublin', 'IRL'],
    'south korea' => ['Seoul, South Korea', 'KOR'],
    'switzerland' => ['Zurich', 'CHE'],
    'mexico city' => ['Mexico City', 'MEX'],
    'unknown location' => ['Mars', null],
    'empty string' => ['', null],
    'null' => [null, null],
]);
