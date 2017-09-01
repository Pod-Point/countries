<?php

namespace PodPoint\Countries\Providers;

use Illuminate\Support\ServiceProvider;

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

        foreach ($this->config as $filename) {
            $config->set($filename, require __DIR__ . '/../config/' . $filename . '.php');
        }
    }
}
