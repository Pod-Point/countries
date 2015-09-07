<?php namespace PodPoint\Admin\Providers;

use Illuminate\Support\ServiceProvider;

class CountriesServiceProvider extends ServiceProvider
{
    /**
     * The configuration files to be loaded indexed by the configuration names.
     *
     * @var array
     */
    private $config = [
        'countries'         => __DIR__ . '../config/countries.php',
        'countries-partial' => __DIR__ . '../config/countries-partial.php'
    ];

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        /** @var \Illuminate\Config\Repository $config */
        $config = $this->app['config'];

        foreach ($this->config as $index => $filename) {
            $config->set('countries', require $filename);
        }
    }
}
