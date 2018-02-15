<?php

namespace PodPoint\Countries;

use Illuminate\Config\Repository;

class CountryHelper
{
    /**
     * The config repository instance.
     *
     * @var Repository
     */
    private $config;

    /**
     * CountryHelper constructor.
     *
     * @param Repository $config
     */
    public function __construct(Repository $config)
    {
        $this->config = $config;
    }

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
