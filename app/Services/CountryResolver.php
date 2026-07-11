<?php

declare(strict_types=1);

namespace App\Services;

class CountryResolver
{
    /**
     * Needle => ISO-3 code. Matched case-insensitively against free-text
     * GitHub locations; longest needles first so "new delhi" beats "delhi".
     */
    private const array NEEDLES = [
        'united states' => 'USA', 'usa' => 'USA', 'san francisco' => 'USA', 'new york' => 'USA', 'seattle' => 'USA', 'austin' => 'USA', 'california' => 'USA',
        'united kingdom' => 'GBR', 'london' => 'GBR', 'england' => 'GBR',
        'ghana' => 'GHA', 'accra' => 'GHA', 'kumasi' => 'GHA',
        'nigeria' => 'NGA', 'lagos' => 'NGA', 'abuja' => 'NGA',
        'india' => 'IND', 'bangalore' => 'IND', 'bengaluru' => 'IND', 'mumbai' => 'IND', 'delhi' => 'IND',
        'germany' => 'GER', 'berlin' => 'GER', 'munich' => 'GER',
        'brazil' => 'BRA', 'brasil' => 'BRA', 'sao paulo' => 'BRA', 'são paulo' => 'BRA', 'rio de janeiro' => 'BRA',
        'netherlands' => 'NED', 'amsterdam' => 'NED', 'holland' => 'NED',
        'france' => 'FRA', 'paris' => 'FRA', 'lyon' => 'FRA',
        'japan' => 'JPN', 'tokyo' => 'JPN', 'osaka' => 'JPN',
        'portugal' => 'POR', 'lisbon' => 'POR', 'porto' => 'POR',
        'argentina' => 'ARG', 'buenos aires' => 'ARG',
        'canada' => 'CAN', 'toronto' => 'CAN', 'vancouver' => 'CAN',
        'australia' => 'AUS', 'sydney' => 'AUS', 'melbourne' => 'AUS',
        'spain' => 'ESP', 'madrid' => 'ESP', 'barcelona' => 'ESP',
        'italy' => 'ITA', 'kenya' => 'KEN', 'nairobi' => 'KEN',
        'south africa' => 'ZAF', 'egypt' => 'EGY', 'poland' => 'POL', 'ukraine' => 'UKR', 'sweden' => 'SWE',
        'indonesia' => 'IDN', 'pakistan' => 'PAK', 'bangladesh' => 'BGD', 'china' => 'CHN',
    ];

    private const string US_STATE_PATTERN = '/,\s*(AL|AK|AZ|AR|CA|CO|CT|DE|FL|GA|HI|ID|IL|IN|IA|KS|KY|LA|ME|MD|MA|MI|MN|MS|MO|MT|NE|NV|NH|NJ|NM|NY|NC|ND|OH|OK|OR|PA|RI|SC|SD|TN|TX|UT|VT|WA|WV|WI|WY|DC)\.?$/i';

    public function resolve(?string $location): ?string
    {
        if ($location === null || trim($location) === '') {
            return null;
        }

        if (preg_match(self::US_STATE_PATTERN, trim($location)) === 1) {
            return 'USA';
        }

        $haystack = mb_strtolower($location);
        $needles = self::NEEDLES;
        uksort($needles, fn (string $a, string $b) => mb_strlen($b) <=> mb_strlen($a));

        foreach ($needles as $needle => $code) {
            if (str_contains($haystack, $needle)) {
                return $code;
            }
        }

        return null;
    }
}
