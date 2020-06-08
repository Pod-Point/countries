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
        return collect($this->config->get('countries'))
            ->where($property, $value)
            ->first();
    }
}
