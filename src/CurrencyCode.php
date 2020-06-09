<?php

namespace PodPoint\I18n;

/**
 * Used as a references for the supported currency codes using the ISO currency codes format.
 */
class CurrencyCode
{
    const EURO = 'EUR';
    const POUND_STERLING = 'GBP';
    const NORWEGIAN_KRONE = 'NOK';

    /**
     * Retrieves all supported currency codes.
     *
     * @return array
     */
    public static function all()
    {
        return [
            self::EURO,
            self::POUND_STERLING,
            self::NORWEGIAN_KRONE,
        ];
    }
}
