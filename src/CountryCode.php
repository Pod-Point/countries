<?php

namespace PodPoint\I18n;

/**
 * Used as a references for the supported country codes using the alpha2/cca2 code format.
 */
class CountryCode
{
    const UNITED_KINGDOM = 'GB';
    const IRELAND = 'IE';
    const NORWAY = 'NO';

    /**
     * Retrieves all supported country codes.
     *
     * @return array
     */
    public static function all()
    {
        return [
            self::UNITED_KINGDOM,
            self::IRELAND,
            self::NORWAY,
        ];
    }
}
