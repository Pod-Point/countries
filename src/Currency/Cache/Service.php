<?php

namespace PodPoint\I18n\Currency\Cache;

use Carbon\Carbon;
use PodPoint\I18n\CurrencyCode;
use Illuminate\Support\Collection;
use PodPoint\I18n\Currency\ExchangeRate;
use Illuminate\Contracts\Cache\Repository as Cache;
use PodPoint\I18n\Currency\Service as CurrencyService;
use PodPoint\I18n\Currency\Service as ServiceInterface;

class Service implements CurrencyService
{
    /**
     * @var Cache
     */
    private $cache;

    /**
     * @var ServiceInterface
     */
    private $service;

    /**
     * @param Cache $cache
     * @param ServiceInterface $service
     */
    public function __construct(Cache $cache, ServiceInterface $service)
    {
        $this->cache = $cache;
        $this->service = $service;
    }

    /**
     * Fetches the exchange rates from another service if they don't already exist in cache.
     *
     * @param string $base
     * @param string[] $currencies
     * @param Carbon|null $timestamp
     *
     * @return Collection|ExchangeRate[]
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function getExchangeRates(string $base = CurrencyCode::POUND_STERLING, array $currencies = [], ?Carbon $timestamp = null): Collection
    {
        if (! $timestamp) {
            $timestamp = Carbon::now();
        }

        $exchangeRates = new Collection();

        foreach ($currencies as $currency) {
            $key = $this->getCacheKey($base, $currency, $timestamp);

            if ($this->cache->has($key)) {
                $rate = $this->cache->get($key);

                $exchangeRate = new ExchangeRate(compact('base', 'currency', 'rate', 'timestamp'));
            } else {
                $rates = $this->service->getExchangeRates($base, $currencies, $timestamp);

                $rates->each(function (ExchangeRate $exchangeRate) {
                    $key = $this->getCacheKey($exchangeRate->base, $exchangeRate->currency, $exchangeRate->timestamp);

                    $this->cache->forever($key, $exchangeRate->rate);
                });

                $exchangeRate = $rates->first(function (ExchangeRate $rate) use ($currency) {
                    return $rate->currency === $currency;
                });
            }

            $exchangeRates->push($exchangeRate);
        }

        return $exchangeRates;
    }

    /**
     * Builds the key used to access/store this rate in cache.
     *
     * @param string $base
     * @param string $currency
     * @param Carbon $timestamp
     *
     * @return string
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    private function getCacheKey(string $base, string $currency, Carbon $timestamp): string
    {
        $key = "rates.{$base}.{$currency}.{$timestamp->format('Y-m-d')}";

        if ($timestamp->isCurrentDay() || $this->cache->has("{$key}.{$timestamp->hour}")) {
            $key .= ".{$timestamp->hour}";
        }

        return $key;
    }
}
