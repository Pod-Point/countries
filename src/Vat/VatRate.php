<?php

namespace PodPoint\I18n\Vat;

class VatRate
{
    public $countryCode;
    public $rate;

    public function __construct(string $countryCode, float $rate)
    {
        $this->countryCode = $countryCode;
        $this->rate = $rate;
    }
}
