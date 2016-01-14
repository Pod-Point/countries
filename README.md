# Countries Package

[ ![Codeship Status for Pod-Point/countries](https://codeship.com/projects/fa54fc40-9d10-0133-2068-4a8fb09a0dfa/status?branch=master)](https://codeship.com/projects/127375) [![Coverage Status](https://coveralls.io/repos/Pod-Point/countries/badge.svg?branch=remove-scrutinizer&service=github&t=EIcVQZ)](https://coveralls.io/github/Pod-Point/countries?branch=remove-scrutinizer) 

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
    "pod-point/countries": "^1.0"
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
