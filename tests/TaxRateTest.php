<?php

namespace PodPoint\I18n\Tests;

use PodPoint\I18n\TaxRate;
use PodPoint\I18n\CountryCode;
use PHPUnit\Framework\TestCase;
use Mpociot\VatCalculator\VatCalculator;

class TaxRateTest extends TestCase
{
    /**
     * @var TaxRate
     */
    private $taxRate;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->taxRate = new TaxRate(new VatCalculator);
    }

    /**
     * Data Provider for testWeCanGetTaxRateForSupportedCountries()
     *
     * @return array
     */
    public function supportedCountriesTaxRateDataProvider()
    {
        return [
            [
                $countryCode = CountryCode::IRELAND,
                $currentVatRate = 0.23,
            ],
            [
                $countryCode = CountryCode::NORWAY,
                $currentVatRate = 0.25,
            ],
            [
                $countryCode = CountryCode::UNITED_KINGDOM,
                $currentVatRate = 0.20,
            ],
        ];
    }

    /**
     * Make sure we can retrieve the tax rate (VAT) for a country.
     * We only test the supported countries through a Data Provider.
     *
     * @dataProvider supportedCountriesTaxRateDataProvider
     *
     * @param CountryCode $countryCode
     * @param float $currentVatRate
     */
    public function testWeCanGetTaxRateForSupportedCountries($countryCode, $currentVatRate)
    {
        $this->assertEquals($currentVatRate, $this->taxRate->get($countryCode));
   }

    /**
     * Make sure that if a Country couldn't be found, the tax rate returned is zero.
     */
    public function testTaxRateWillBeZeroIfCountryIsNotFound()
    {
        $this->assertEquals(0, $this->taxRate->get('foobar'));
    }

    /**
     * Data Provider for testWeCanCalculatePriceWithVat()
     *
     * @return array
     */
    public function supportedCountriesCalculationDataProvider()
    {
        return [
            [
                $countryCode = CountryCode::IRELAND,
                $netPrice = 100.00,
                $vatPrice = 123.00,
            ],
            [
                $countryCode = CountryCode::NORWAY,
                $netPrice = 100.00,
                $vatPrice = 125.00,
            ],
            [
                $countryCode = CountryCode::UNITED_KINGDOM,
                $netPrice = 100.00,
                $vatPrice = 120.00,
            ],
        ];
    }

    /**
     * Make sure we can calculate the price after tax (with VAT) based on a net price, before tax,
     * for a specific country. We only test the supported countries through a Data Provider.
     *
     * @param string $countryCode
     * @param float $netPrice
     * @param float $vatPrice
     *
     * @dataProvider supportedCountriesCalculationDataProvider
     */
    public function testWeCanCalculatePriceWithVat(string $countryCode, float $netPrice, float $vatPrice)
    {
        $this->assertEquals($vatPrice, $this->taxRate->calculate($netPrice, $countryCode));
    }

    /**
     * Data Provider for testWeCanCalculatePriceExcludingVat()
     *
     * @return array
     */
    public function supportedCountriesExclusionDataProvider()
    {
        return [
            [
                $countryCode = CountryCode::IRELAND,
                $grossPrice = 123.00,
                $exVatPrice = 100.00,
            ],
            [
                $countryCode = CountryCode::NORWAY,
                $grossPrice = 125.00,
                $exVatPrice = 100.00,
            ],
            [
                $countryCode = CountryCode::UNITED_KINGDOM,
                $grossPrice = 120.00,
                $exVatPrice = 100.00,
            ],
        ];
    }

    /**
     * Make sure we can calculate the price before tax (excluding VAT) based on a gross price, after tax,
     * for a specific country. We only test the supported countries through a Data Provider.
     *
     * @param string $countryCode
     * @param float $grossPrice
     * @param float $exVatPrice
     *
     * @dataProvider supportedCountriesExclusionDataProvider
     */
    public function testWeCanCalculatePriceExcludingVat(string $countryCode, float $grossPrice, float $exVatPrice)
    {
        $this->assertEquals($exVatPrice, $this->taxRate->exclude($grossPrice, $countryCode));
    }
}
