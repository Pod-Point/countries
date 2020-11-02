<?php

namespace PodPoint\I18n\Providers;

use PodPoint\I18n\CountryHelper;
use PodPoint\I18n\TaxRate;
use League\ISO3166\ISO3166;
use PodPoint\I18n\CurrencyHelper;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;
use Mpociot\VatCalculator\VatCalculator;

class CountriesServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfig();

        $this->registerBindings();

        $this->registerFacades();
    }

    /**
     * Merges user's and application's configs before injecting additional information
     * into the loaded configs (enhancing).
     *
     * @return void
     */
    protected function mergeConfig()
    {
        collect([
            'countries',
            'countries-partial',
        ])->each(function ($key) {
            $this->app->config->set($key, array_merge(
                require __DIR__ . "/../config/$key.php",
                $this->app->config->get($key, [])
            ));
        });

        $this->enhanceConfig();
    }

    /**
     * Here we'll merge the standard countries config file with additional information from
     * 'league/iso3166' package as well as some miscellaneous data we manually add for
     * Laravel support, like the `locale` for example.
     *
     * @return void
     */
    protected function enhanceConfig()
    {
        $countries = collect($this->app->config->get('countries'));
        $partialCountries = collect($this->app->config->get('countries-partial'));

        /** @var Collection $enhancedCountries */
        $enhancedCountries = $countries->pipe(function ($countries) {
            return $this->addIsoInfoToCountryConfig($countries);
        })->pipe(function ($countries) use ($partialCountries) {
            return $this->addPartialInfoToCountryConfig($countries, $partialCountries);
        });

        $onlySupportedCountries = $enhancedCountries->whereIn('alpha2', $partialCountries->keys());

        $this->app->config->set('countries', $enhancedCountries
            ->sortBy(function ($country, $key) { return $key; })
            ->toArray());
        $this->app->config->set('countries-partial', $onlySupportedCountries
            ->sortBy(function ($country, $key) { return $key; })
            ->toArray());
    }

    /**
     * Adds ISO country information to our existing country configuration.
     *
     * @param Collection $countries
     *
     * @return Collection
     */
    protected function addIsoInfoToCountryConfig(Collection $countries): Collection
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
    protected function addPartialInfoToCountryConfig(Collection $countries, Collection $partialCountries): Collection
    {
        return $countries->transform(function ($countryDefinition, $alpha2) use ($partialCountries) {
            return array_merge($countryDefinition, $partialCountries->get($alpha2) ?? []);
        });
    }

    /**
     * Register the application bindings.
     *
     * @return void
     */
    protected function registerBindings()
    {
        $this->app->singleton('currency.helper', function ($app) {
            return new CurrencyHelper($app->config);
        });

        $this->app->singleton('i18n.taxrate', function ($app) {
            return new TaxRate(new VatCalculator($app->config));
        });

        $this->app->singleton('country.helper', function ($app) {
            return new CountryHelper($app->config);
        });
    }

    /**
     * Register the facades without the user having to add it to the app.php file.
     *
     * @return void
     */
    protected function registerFacades()
    {
        $this->app->booting(function () {
            $this->app->alias('currency.helper', CurrencyHelper::class);
            $this->app->alias('i18n.taxrate', TaxRate::class);
            $this->app->alias('country.helper', CountryHelper::class);
        });
    }
}
