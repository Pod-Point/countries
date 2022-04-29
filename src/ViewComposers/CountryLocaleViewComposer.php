<?php

namespace PodPoint\I18n\ViewComposers;

use Illuminate\Config\Repository;
use Illuminate\View\View;

class CountryLocaleViewComposer
{
    /**
     * Instance of Config Repository.
     *
     * @var Repository
     */
    protected $config;

    /**
     * CountryLocaleViewComposer constructor.
     *
     * @param  Repository  $config
     */
    public function __construct(Repository $config)
    {
        $this->config = $config;
    }

    /**
     * Fetches the supported application locales for Laravel and their "Display names" from the
     * enhanced configuration array and binds the data to the view.
     *
     * @param  View  $view
     *
     * @see \PodPoint\I18n\CountriesServiceProvider::addIsoInfoToCountryConfig()
     */
    public function compose(View $view)
    {
        $countryLocalesOptions = collect($this->config->get('countries-partial', []))
            ->pluck('language', 'locale')
            ->toArray();

        $view->with(compact('countryLocalesOptions'));
    }
}
