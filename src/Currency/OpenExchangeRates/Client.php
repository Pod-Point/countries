<?php

namespace PodPoint\I18n\Currency\OpenExchangeRates;

use Illuminate\Support\Str;
use Illuminate\Contracts\Config\Repository;

class Client extends \GuzzleHttp\Client
{
    /**
     * @param Repository $configRepository
     * @param array $config
     */
    public function __construct(Repository $configRepository, array $config = [])
    {
        $baseUrl = $configRepository->get('services.oxr.url', 'https://openexchangerates.org/api');

        parent::__construct(array_merge($config, [
            'base_uri' => Str::finish($baseUrl, '/'),
        ]));
    }
}
