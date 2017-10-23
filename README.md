# Countries Package

[![Build Status](https://travis-ci.org/Pod-Point/countries.svg?branch=master)](https://travis-ci.org/Pod-Point/countries) [![codecov](https://codecov.io/gh/Pod-Point/countries/branch/master/graph/badge.svg?token=kG5ptGaEFs)](https://codecov.io/gh/Pod-Point/countries) [![Packagist](https://img.shields.io/packagist/v/Pod-Point/countries.svg)](https://packagist.org/packages/pod-point/countries)

This is a little package that provides Laravel and Lumen applications with full and partial lists of country names and international dialling codes. The
country lists are loaded into config using using the keys `countries` and `countries-partial`. The countries are indexed
using their uppercase ISO code.

## Installation

Require the package in composer:

```javascript
"require": {
    "pod-point/countries": "^2.0"
},
```

Then finally, if you're using Laravel, add the service provider to your `config/app.php` providers array:

```php
'providers' => [
    PodPoint\Countries\Providers\CountriesServiceProvider::class
]
```

If you're using Lumen, add the following line to your `bootstrap/app.php` file:

```php
$app->register(PodPoint\Countries\Providers\CountriesServiceProvider::class);
```
