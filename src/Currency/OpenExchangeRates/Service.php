<?php

namespace PodPoint\I18n\Currency\OpenExchangeRates;

use Carbon\Carbon;
use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Support\Collection;
use PodPoint\I18n\Currency\Service as CurrencyService;
use PodPoint\I18n\Currency\ExchangeRate;

class Service implements CurrencyService
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var Client
     */
    private $client;

    /**
     * @param Config $config
     * @param Client $client
     */
    public function __construct(Config $config, Client $client)
    {
        $this->config = $config;
        $this->client = $client;
    }

    /**
     * Fetches the exchange rates from the OpenExchangeRates API.
     *
     * @param string $base
     * @param string[] $currencies
     * @param Carbon|null $timestamp
     *
     * @return Collection|ExchangeRate[]
     */
    public function getExchangeRates(string $base = 'GBP', array $currencies = [], Carbon $timestamp = null)
    {
        $appId = $this->config->get('services.oxr.appId');

        $endpoint = $timestamp->isCurrentDay() ?
            'latest.json' :
            "historical/{$timestamp->format('Y-m-d')}.json";

        $params = [
            'app_id' => $appId,
            'base' => $base,
        ];

        if ($currencies) {
            $params['symbols'] = implode(',', $currencies);
        }

        $query = http_build_query($params);

        $response = $this->client->get("{$endpoint}?{$query}");
        $json = \GuzzleHttp\json_decode($response->getBody()->getContents(), true);
        $timestamp = Carbon::createFromTimestamp($json['timestamp']);
        $rates = $json['rates'];

        $exchangeRates = new Collection();

        foreach ($rates as $currency => $rate) {
            $exchangeRate = new ExchangeRate(compact('base', 'currency', 'rate', 'timestamp'));

            $exchangeRates->push($exchangeRate);
        }

        return $exchangeRates;
    }
}
