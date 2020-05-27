<?php

namespace PodPoint\I18n\Vat;

use PodPoint\I18n\CountryCode;

interface Service
{
    public function getVatRate(CountryCode $countryCode): ?VatRate;
}
