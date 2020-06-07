<?php

namespace PodPoint\I18n\Facades;

class TaxRate extends \Illuminate\Support\Facades\Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'taxrate';
    }
}
