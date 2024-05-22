<?php

/*
|--------------------------------------------------------------------------
| Partial List of Supported Countries
|--------------------------------------------------------------------------
|
| A partial list of supported country uppercase ISO country code (alpha2).
| This so we can:
| * Define the limited supported countries for our system.
| * Add any miscellaneous information to a specific Country.
|
| Note: this will also end up merged within the main `countries` config entries.
|
*/

use PodPoint\I18n\CountryCode;

return [
    CountryCode::UNITED_KINGDOM => [
        'systemLocale' => 'en_GB.UTF-8',
        'locale' => 'en',
        'language' => 'ENG',
        'tld' => 'com',
        'timezone' => 'Europe/London',
        'minorUnitEnd' => 99,
        'minorUnitPattern' => '#.##p',
    ],
    CountryCode::IRELAND => [
        'systemLocale' => 'en_IE.UTF-8',
        'locale' => 'ie',
        'language' => 'ENG',
        'tld' => 'ie',
        'timezone' => 'Europe/Dublin',
    ],
    CountryCode::NORWAY => [
        'locale' => 'no',
        'systemLocale' => 'nb_NO.UTF-8',
        'language' => 'NOR',
        'tld' => 'no',
        'timezone' => 'Europe/Oslo',
    ],
    CountryCode::FRANCE => [
        'locale' => 'fr',
        'systemLocale' => 'fr_FR.UTF-8',
        'language' => 'FRA',
        'tld' => 'fr',
        'timezone' => 'Europe/Paris',
    ],
    CountryCode::SPAIN => [
        'locale' => 'es',
        'systemLocale' => 'es_ES.UTF-8',
        'language' => 'SPA',
        'tld' => 'es',
        'timezone' => 'Europe/Madrid',
    ],
];
