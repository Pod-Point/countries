<?php

namespace PodPoint\I18n;

/**
 * Used as a references for the supported country codes using the alpha2/cca2 code format.
 */
class CountryCode
{
    const IRELAND = 'IE';
    const NORWAY = 'NO';
    const UNITED_KINGDOM = 'GB';

    /**
     * Retrieves all supported country codes.
     *
     * @return array
     */
    public static function all()
    {
        return [
            self::IRELAND,
            self::NORWAY,
            self::UNITED_KINGDOM,
        ];
    }
}
