<?php

namespace PodPoint\I18n;

use Illuminate\Contracts\Config\Repository;

abstract class Helper
{
    /**
     * The config repository instance.
     *
     * @var Repository
     */
    protected $config;

    /**
     * CountryHelper constructor.
     *
     * @param Repository $config
     */
    public function __construct(Repository $config)
    {
        $this->config = $config;
    }
}
