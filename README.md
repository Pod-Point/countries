# Countries Package

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Pod-Point/countries/badges/quality-score.png?b=master&s=1de994cf3d5d2d2e48110c4df8a9c666c48c615e)](https://scrutinizer-ci.com/g/Pod-Point/countries/?branch=master) [![Code Coverage](https://scrutinizer-ci.com/g/Pod-Point/countries/badges/coverage.png?b=master&s=971f286b408e3bdb2cdfa1e15bfd97970dba83d3)](https://scrutinizer-ci.com/g/Pod-Point/countries/?branch=master) [![Build Status](https://scrutinizer-ci.com/g/Pod-Point/countries/badges/build.png?b=master&s=dd155cf7a326ef4db6d5209d19a972e81528e4e0)](https://scrutinizer-ci.com/g/Pod-Point/countries/build-status/master)

This is a little package that provides Laravel and Lumen applications with full and partial lists of countries. The
country lists are loaded into config using using the keys `countries` and `countries-partial`. The countries are indexed
using their uppercase ISO code.

## Installation

This is a private package, so you need to add the repository to your `composer.json` file and make sure that you have
specified an access token for composer to access GitHub using your identity:

```javascript
"repositories": [
    {
        "type": "git",
        "url": "git@github.com:pod-point/countries.git"
    }
]
```

Then require the package:

```javascript
"require": {
    "pod-point/countries": "~1.0"
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
