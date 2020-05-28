<?php

namespace PodPoint\I18n\Vat;

use PodPoint\I18n\CountryCode;

interface Service
{
    /**
     * Returns vat rate for a specific country.
     *
     * @param CountryCode $countryCode
     *
     * @return VatRate
     */
    public function getVatRate(CountryCode $countryCode): VatRate;
}
