<?php

namespace PodPoint\I18n\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static array all()
 */
class Locale extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'i18n.locale';
    }
}
