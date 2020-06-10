<?php

namespace PodPoint\I18n;

use Illuminate\Config\Repository;

class Locale
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

    /**
     * Returns all languages names supported indexed by their locales.
     *
     * @return array
     */
    public function all(): array
    {
        return collect($this->config->get('countries-partial'))
            ->pluck('language', 'locale')
            ->toArray();
    }
}
