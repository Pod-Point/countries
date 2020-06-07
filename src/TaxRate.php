<?php

namespace PodPoint\I18n;

use Mpociot\VatCalculator\VatCalculator;

class TaxRate
{
    /**
     * @var VatCalculator
     */
    private $vatCalculator;

    public function __construct(VatCalculator $vatCalculator)
    {
        $this->vatCalculator = $vatCalculator;
    }

    /**
     * @param string $countryCode
     *
     * @return float
     */
    public function get(string $countryCode): float
    {
        return (float) $this->vatCalculator->getTaxRateForLocation($countryCode);
    }

    /**
     * @param int|float $netPrice
     * @param string|null $countryCode
     * @param null $postalCode
     * @param null $company
     * @param null $type
     *
     * @return float
     */
    public function calculate($netPrice, string $countryCode = null, $postalCode = null, $company = null, $type = null): float
    {
        return (float) $this->vatCalculator->calculate($netPrice, $countryCode, $postalCode, $company, $type);
    }
}
