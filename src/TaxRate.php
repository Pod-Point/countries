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
     * Returns the tax rate for the given country code.
     *
     * @param string $countryCode
     *
     * @return float
     */
    public function get(string $countryCode): float
    {
        return (float) $this->vatCalculator->getTaxRateForCountry($countryCode);
    }

    /**
     * Calculate the VAT based on the net price, country code and indication if the
     * customer is a company or not.
     *
     * @param int|float $netPrice The net price to use for the calculation
     * @param string $countryCode The country code to use for the rate lookup
     * @param string|null $postalCode The postal code to use for the rate exception lookup
     * @param bool|null $company Whether or not the customer is a company
     * @param string|null $type The type can be low or high
     *
     * @return float
     */
    public function calculate($netPrice, string $countryCode, $postalCode = null, $company = null, $type = null): float
    {
        return (float) $this->vatCalculator->calculate($netPrice, $countryCode, $postalCode, $company, $type);
    }

    /**
     * Calculate the net price on the gross price, country code and indication if the
     * customer is a company or not.
     *
     * @param int|float $grossPrice The gross price to use for the calculation
     * @param string $countryCode The country code to use for the rate lookup
     * @param string|null $postalCode The postal code to use for the rate exception lookup
     * @param bool|null $company Whether or not the customer is a company
     * @param string|null $type The type can be low or high
     *
     * @return float
     */
    public function exclude($grossPrice, string $countryCode, $postalCode = null, $company = null, $type = null): float
    {
        return (float) $this->vatCalculator->calculateNet($grossPrice, $countryCode, $postalCode, $company, $type);
    }
}
