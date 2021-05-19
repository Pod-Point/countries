# Changelog

All notable changes to `pod-point/countries` will be documented in this file.

## 5.0.0 - 2021-XX-XX

* Major release to support PHP 7.3+ and Laravel 7.x+
  * Illuminate packages 6.* -> 7.* or 8.*
  * league/iso3166 2.x -> 3.x
  * PHPUnit 8.x -> 8.x or 9.x
* Implement Laravel Auto-Discovery
* Moved `config/` a level up following best practices.
* Moved `CountriesServiceProvider.php` a level up following best practices.
* Removed php-coveralls
* Moved from Travis CI to Github Actions

#### Breaking changes:

* This package can no longer support PHP7.2 - if using PHP7.2 please use version 4.*.

## 4.0.1 - 2021-05-18

* Implement Laravel Auto-Discovery
* Moved `config/` a level up following best practices.
* Moved `CountriesServiceProvider.php` a level up following best practices.
* Removed php-coveralls
* Moved from Travis CI to Github Actions

## 4.0.0 - 2021-04-29

* Major release to support PHP 7.2 and Laravel 6.x
  * Illuminate packages 5.* -> 6.*
  * PHPUnit 7.x -> 8.x
  * Guzzle 6.x -> 7.x
  * Removal of satooshi/php-coveralls in favour of php-coveralls/php-coveralls

#### Breaking changes:

* This package can no longer support PHP7.1 - if using PHP7.1 please use version 3.*.

## 3.1.2 - 2021-05-18

* Minor modernisations
* Moved from Travis CI to Github Actions

## 3.1.1 - 2020-11-05

* `getSystemLocale` in `LocalizedHelper` now returns the fallback locale if the given one is not found. This created issues with some tests because NumberFormatter becomes system dependent if null is given.

## 3.1.0 - 2020-11-03

* Add `CountryHelper` facade with `getCountryCodeFromLocale` and `findByLocale` functions.
* Improve `CurrencyHelper` facade with a new function `toStandardFormat`. It returns the typical currency format and can also show a specified number of decimals.

## 3.0.9 - 2020-10-12

* Add `formatToMinorUnitWhenApplicable` method: formats currency with pence when the rate is less than 1.00.

## 3.0.8 - 2020-07-01

* [SWP-1718](https://podpoint.atlassian.net/browse/SWP-1718) - Revert “Republic of Ireland” to have its locale set to ie instead of en

## 3.0.7 - 2020-06-09

### [SWP-1648] DT breaking change on i18n v 3.0.6

* Fix locale for supported countries (should only be `en` or `no` for now)
* Renaming wrongly named test files.
* Improve test coverage.
* Improve ViewComposer tests.

### [SWP-1609] [ALT] Extract VAT logic into i18n package

* Leverage `mpociot/vat-calculator` in order to bring VAT resolution support
* Exposing it through a Facade too
* Improve service provider
* Better type hinting
* Update readme

## 3.0.6 - 2020-04-21

* Update Ireland details
* Add EUR as currency code
* IE as country code

## 3.0.5 - 2019-07-08

* Reverting the order when merging countries so that the name in our config is the one that is going to be used.

## 3.0.4 - 2019-07-04

### CurrencyHelper: up to 6 decimals of a monetary unit supported

* PHP NumberFormatter will round up with 2 decimals only by default.
* Sometimes we can display up to 6 decimals of the monetary unit (ex: `£0.106544`) especially for energy prices like `£0.106544 per kWh`.
* If a regular float like `19.99` with only 2 decimals is given, it will still be formatted as usual `£19.99`.

## 3.0.3 - 2019-06-26

* [SWP-36](https://podpoint.atlassian.net/browse/SWP-36) Adding to format from cents helper method with the new `toFormatFromInt` and `moneyFormatFromInt` helpers method.

## 3.0.2 - 2019-05-22

* Adding new abstract `LocalizedHelper` that will have the logic to retrieve a locale in a `NumberFormatter` format
* Changing the currency helper so that it extends the localized helper
* Adding new number helper that allows us to format a number in the right locale
* renaming `format` to `toFormat` for consistency in the `NumberHelper`
* Changing the style from `DECIMAL` to `DEFAULT_STYLE`

## 3.0.0 - 2019-03-14

### Added

* Support of currency conversion service
* Helpers to format money given the currency and locale
* Helper to get the currency symbol given the currency code and locale

### Updated

* Namespace was update to `PodPoint\I18n`

## 2.4.4 - 2018-09-12

* Add GB system locale

## 2.4.3 - 2018-09-04

* Add Norway and UK timezones

## 2.4.2 - 2018-02-21

* Add system locale to NO

## 2.4.1 - 2018-02-15

* Added country helper

## 2.4.0 - 2018-02-01

* Add ISO country information to existing country config

## 2.3.2 - 2018-01-26

* Adding the extension linked to the countries in `countries-partial` config files
* Renaming the index 'extension' to 'tld'

## 2.3.1/2.3.0 - 2018-01-23

* Adding locale informations in config file `contries-partials.php`, and a new view composer that prepares values for language switchers
* Adding language field into configuration files for the languages switchers

## 2.2.0 - 2018-01-19

* Add country code constants

## 2.1.0 - 2017-10-23

* Add country code view composer

## 2.0.1 - 2017-09-01

* Fix to country with no dialling code: `null` value where no dialling code

## 2.0 - 2017-08-31

* Added dialling codes

#### Breaking changes:

* This is a breaking change from v1.0.* as config arrays have been restructured to include dialling codes.

## 1.0.1 - 2015-12-07

* Support Laravel 5.1 and 5.2

## 1.0.0 - 2015-09-07

* Initial release
