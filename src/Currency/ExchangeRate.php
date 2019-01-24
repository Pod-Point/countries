<?php

namespace PodPoint\I18n\Currency;

use Carbon\Carbon;

class ExchangeRate
{
    /**
     * The base currency code.
     *
     * @var string
     */
    public $base;

    /**
     * The target currency code.
     *
     * @var string
     */
    public $currency;

    /**
     * The currency rate.
     *
     * @var float
     */
    public $rate;

    /**
     * The datetime of the exchange rate.
     *
     * @var Carbon
     */
    public $timestamp;

    /**
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->base = array_get($attributes, 'base', 'GBP');
        $this->currency = array_get($attributes, 'currency');
        $this->rate = array_get($attributes, 'rate');
        $this->timestamp = array_get($attributes, 'timestamp', Carbon::now());
    }
}
