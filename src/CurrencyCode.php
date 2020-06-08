<?php

namespace PodPoint\I18n;

/**
 * Used as a references for the supported currency codes using the ISO currency codes format.
 */
class CurrencyCode
{
    const EURO = 'EUR';
    const NORWEGIAN_KRONE = 'NOK';
    const POUND_STERLING = 'GBP';

    /**
     * Retrieves all supported currency codes.
     *
     * @return array
     */
    public static function all()
    {
        return [
            self::EURO,
            self::NORWEGIAN_KRONE,
            self::POUND_STERLING,
        ];
    }
}
