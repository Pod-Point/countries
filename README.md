# Internationalisation Package

[![Build Status](https://travis-ci.org/Pod-Point/countries.svg?branch=master)](https://travis-ci.org/Pod-Point/countries) [![Packagist](https://img.shields.io/packagist/v/Pod-Point/countries.svg)](https://packagist.org/packages/pod-point/countries)

This package provides Laravel and Lumen applications internationalisation features:

- List of all countries with data such as dialing codes, names, currencies...
- Limited countries supported by our applications with additional data such as locale, language...
- Laravel ViewComposers with some data pre-loaded.
- Facades and helpers for country, currencies and tax rates (VAT).
- Exchange rates for currencies via OpenExchangeRate's API implementation.

The countries are indexed using their uppercase ISO codes (alpha2/cca2).

## Installation

Require the package in composer:

For Laravel 5.* and PHP <= 7.1
```javascript
"require": {
    "pod-point/countries": "^3.0"
},
```

For Laravel 6.* and PHP >= 7.2
```javascript
"require": {
    "pod-point/countries": "^4.0"
},
```

Then finally, if you're using Laravel, add the service provider to your `config/app.php` providers array:

```php
'providers' => [
    PodPoint\I18n\Providers\CountriesServiceProvider::class,
]
```

If you're using Lumen, add the following line to your `bootstrap/app.php` file:

```php
$app->register(PodPoint\I18n\Providers\CountriesServiceProvider::class);
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.
