<?php

namespace PodPoint\I18n\Vat\Providers;

use GuzzleHttp\Client;

class Api
{
    /**
     * @var Client
     */
    protected $client;

    public function __construct()
    {
        $this->client = new Client();
    }
}
