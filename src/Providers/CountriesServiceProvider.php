<?php

namespace PodPoint\Countries\Providers;

use Illuminate\Support\ServiceProvider;
use League\ISO3166\ISO3166;

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
        $countryConfigFiles = ['countries', 'countries-partial'];

        foreach ($this->config as $filename) {
            $data = require __DIR__ . '/../config/' . $filename . '.php';

            if (in_array($filename, $countryConfigFiles)) {
                $data = $this->addIsoInfoToCountryConfig($data, $filename);
            }

            $config->set($filename, $data);
        }
    }

    /**
     * Adds ISO country information to our existing country configuration.
     *
     * @param array $data
     * @param string $filename
     *
     * @return array
     */
    private function addIsoInfoToCountryConfig(array $data, string $filename)
    {
        $isoCountries = (new ISO3166())->all();

        foreach ($isoCountries as $isoData) {
            $countryCode = $isoData['alpha2'];
            $configData = array_get($data, $countryCode, []);

            if ($filename === 'countries-partial' && !$configData) {
                continue;
            }

            array_set($data, $countryCode, array_merge($configData, $isoData));
        }

        return $data;
    }
}
