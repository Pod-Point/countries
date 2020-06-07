<?php

namespace PodPoint\I18n\Facades;

/**
 * @method static string getSymbol()
 * @method static string toFormat()
 */
class CurrencyHelper extends \Illuminate\Support\Facades\Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'currency.helper';
    }
}
