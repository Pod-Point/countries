<?php

namespace PodPoint\I18n\Providers;

use Illuminate\Config\Repository;
use Illuminate\Support\ServiceProvider;
use League\ISO3166\ISO3166;
use PodPoint\I18n\CurrencyHelper;

class CountriesServiceProvider extends ServiceProvider
{
    /**
     * The configuration files to be loaded indexed by the configuration names.
     *
     * @var array
     */
    private $config = [
        'countries',
        'countries-partial'
    ];

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        /** @var \Illuminate\Config\Repository $config */
        $config = $this->app->config;

        $this->setCountryConfig($config);

        $this->app->singleton('currency.helper', function ($app) {
            return new CurrencyHelper($this->app->config);
        });

        $this->app->alias('currency.helper', CurrencyHelper::class);
    }

    /**
     * Adds ISO country information to our existing country configuration.
     *
     * @param array $data
     *
     * @return array
     */
    private function addIsoInfoToCountryConfig(array $data)
    {
        $isoCountries = (new ISO3166())->all();

        foreach ($isoCountries as $isoData) {
            $countryCode = $isoData['alpha2'];
            $configData = array_get($data, $countryCode, []);

            array_set($data, $countryCode, array_merge($isoData, $configData));
        }

        return $data;
    }

    /**
     * Sets the country configuration.
     *
     * @param Repository $config
     */
    private function setCountryConfig(Repository $config)
    {
        $countries = require __DIR__ . '/../config/countries.php';
        $countries = $this->addIsoInfoToCountryConfig($countries);

        $config->set('countries', $countries);

        $this->setPartialCountryConfig($config, $countries);
    }

    /**
     * Sets the partial country configuration.
     *
     * @param Repository $config
     * @param array $countries
     */
    private function setPartialCountryConfig(Repository $config, array $countries)
    {
        $partialCountryList = require __DIR__ . '/../config/countries-partial.php';
        $partialCountryConfig = array_filter(
            $countries,
            function ($countryCode) use ($partialCountryList) {
                return in_array($countryCode, $partialCountryList);
            },
            ARRAY_FILTER_USE_KEY
        );

        $config->set('countries-partial', $partialCountryConfig);
    }
}
