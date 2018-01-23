<?php

namespace PodPoint\Countries\ViewComposers;

use Illuminate\View\View;
use Illuminate\Config\Repository;

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
     * @param Repository $config
     */
    public function __construct(Repository $config)
    {
        $this->config = $config;
    }

    /**
     * Binds the data to the view.
     *
     * @param View $view
     */
    public function compose(View $view)
    {
        $view->with(['countryLocalesOptions' => $this->countryLocaleOptions()]);
    }

    /**
     * Get the country-locale options by looping the countries-partial in the config.
     *
     * @return array
     */
    private function countryLocaleOptions()
    {
        $countriesPartial = $this->config->get('countries-partial');
        $countriesLocaleOptions = [];

        foreach ($countriesPartial as $countryCode => $country) {
            $countriesLocaleOptions[$country['locale']] = $countryCode;
        }

        return $countriesLocaleOptions;
    }
}
