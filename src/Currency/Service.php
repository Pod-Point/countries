<?php

namespace PodPoint\I18n\Currency;

use Carbon\Carbon;
use Illuminate\Support\Collection;

interface Service
{
    /**
     * Returns the rates of conversion from the given base currency.
     * Passing an array of currencies will restrict the set of results to only the given currencies.
     * Passing a timestamp will retrieve the rates from that given date (& time if possible).
     *
     * @param string $base
     * @param string[] $currencies
     * @param Carbon|null $timestamp
     *
     * @return Collection|ExchangeRate[]
     */
    public function getExchangeRates(string $base = 'GBP', array $currencies = [], Carbon $timestamp = null);
}
