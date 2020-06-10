<?php

namespace PodPoint\I18n\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static array all(string $translateIn = null)
 */
class Language extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'i18n.language';
    }
}
