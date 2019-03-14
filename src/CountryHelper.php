<?php

namespace PodPoint\I18n;

class CountryHelper extends Helper
{
    /**
     * Returns the first country matching the given property/value pair.
     *
     * @param string $property
     * @param $value
     *
     * @return array|null
     */
    public function findBy(string $property, $value)
    {
        $countries = $this->config->get('countries');

        return array_first(
            $countries,
            function ($country) use ($property, $value) {
                return array_get($country, $property) === $value;
            }
        );
    }
}
