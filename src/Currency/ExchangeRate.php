<?php

namespace PodPoint\I18n\Currency;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use PodPoint\I18n\CurrencyCode;

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
     * @param  array  $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->base = Arr::get($attributes, 'base', CurrencyCode::POUND_STERLING);

        $this->currency = Arr::get($attributes, 'currency');

        $this->rate = Arr::get($attributes, 'rate');

        $this->timestamp = Arr::get($attributes, 'timestamp', Carbon::now());
    }
}
