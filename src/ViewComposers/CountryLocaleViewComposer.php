<?php

namespace PodPoint\Countries\ViewComposers;

use Illuminate\View\View;

class CountryLocaleViewComposer
{
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
        $countriesPartial = config('countries-partial');
        $countriesLocaleOptions = [];

        foreach ($countriesPartial as $countryCode => $country) {
            $countriesLocaleOptions[$country['locale']] = $country['languageSwitcherLabel'];
        }

        return $countriesLocaleOptions;
    }
}
