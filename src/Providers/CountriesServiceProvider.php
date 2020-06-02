<?php

namespace PodPoint\I18n\Providers;

use League\ISO3166\ISO3166;
use PodPoint\I18n\CurrencyHelper;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Collection;

class CountriesServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->loadConfiguration();

        $this->app->singleton('currency.helper', function ($app) {
            return new CurrencyHelper($this->app->config);
        });

        $this->app->alias('currency.helper', CurrencyHelper::class);
    }

    /**
     * Here we'll merge the standard countries config file with additional information from
     * 'league/iso3166' package as well as some miscellaneous data we manually add for
     * Laravel support, like the `locale` for example.
     *
     * @return void
     */
    private function loadConfiguration()
    {
        $countries = collect(require __DIR__ . '/../config/countries.php');

        $partialCountries = collect(require __DIR__ . '/../config/countries-partial.php');

        /** @var Collection $enhancedCountries */
        $enhancedCountries = $countries->pipe(function ($countries) {
            return $this->addIsoInfoToCountryConfig($countries);
        })->pipe(function ($countries) use ($partialCountries) {
            return $this->addPartialInfoToCountryConfig($countries, $partialCountries);
        });

        $this->app->config->set('countries', $enhancedCountries->toArray());

        $onlySupportedCountries = $enhancedCountries->whereIn('alpha2', $partialCountries->keys());

        $this->app->config->set('countries-partial', $onlySupportedCountries->toArray());
    }

    /**
     * Adds ISO country information to our existing country configuration.
     *
     * @param Collection $countries
     *
     * @return Collection
     */
    private function addIsoInfoToCountryConfig(Collection $countries)
    {
        return $countries->transform(function ($countryDefinition, $countryCode) {
            return array_merge((new ISO3166)->alpha2($countryCode), $countryDefinition);
        })->sortBy('alpha2');
    }

    /**
     * Adds miscellaneous country information, like the Laravel locale for each country for example,
     * to our existing country configuration.
     *
     * @param Collection $countries
     * @param Collection $partialCountries
     *
     * @return Collection
     */
    private function addPartialInfoToCountryConfig(Collection $countries, Collection $partialCountries)
    {
        return $countries->transform(function ($countryDefinition, $alpha2) use ($partialCountries) {
            return array_merge($countryDefinition, $partialCountries->get($alpha2) ?? []);
        });
    }
}
