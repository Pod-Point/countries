<?php

namespace PodPoint\I18n\Tests\Unit;

use PodPoint\I18n\CurrencyCode;
use PodPoint\I18n\CurrencyHelper;
use PodPoint\I18n\Tests\TestCase;

class CurrencyHelperTest extends TestCase
{
    /**
     * Data provider for testToFormat.
     *
     * @return array
     */
    public function providerTestToFormat()
    {
        $value = 1500.5;

        return [
            'pound sterling in english' => [
                $value,
                CurrencyCode::POUND_STERLING,
                'en',
                '£1,500.50',
            ],
            'pound sterling in norwegian' => [
                $value,
                CurrencyCode::POUND_STERLING,
                'no',
                '£ 1 500,50',
            ],
        ];
    }

    /**
     * Tests that it returns float values properly formatted according to locale and currency.
     *
     * @dataProvider providerTestToFormat
     *
     * @param float $value
     * @param string $currencyCode
     * @param string $locale
     * @param string $expected
     */
    public function testToFormat(float $value, string $currencyCode, string $locale, string $expected)
    {
        $this->loadConfiguration()->loadCountriesServiceProvider();

        $actual = (new CurrencyHelper($this->app->config))->toFormat(
            $value,
            $currencyCode,
            $locale
        );

        $this->assertEquals($expected, $actual);
    }

    /**
     * Data provider for testGetSymbol.
     *
     * @return array
     */
    public function providerTestGetSymbol()
    {
        return [
            'Pound Sterling' => [
                CurrencyCode::POUND_STERLING,
                'en',
                '£',
            ],
            'Norwegian Krone' => [
                CurrencyCode::NORWEGIAN_KRONE,
                'no',
                'kr',
            ],
        ];
    }

    /**
     * Tests that it returns a currency symbol.
     *
     * @dataProvider providerTestGetSymbol
     *
     * @param string $currencyCode
     * @param string $locale
     * @param string $expected
     */
    public function testGetSymbol(string $currencyCode, string $locale, string $expected)
    {
        $this->loadConfiguration()->loadCountriesServiceProvider();

        $actual = (new CurrencyHelper($this->app->config))->getSymbol($currencyCode, $locale);

        $this->assertEquals($expected, $actual);
    }

    /**
     * Tests that it returns formatted value fractional monetary values.
     */
    public function testToFormatFromInt()
    {
        $this->loadConfiguration()->loadCountriesServiceProvider();

        $expected = '£15.50';
        $actual = (new CurrencyHelper($this->app->config))->toFormatFromInt('1550');

        $this->assertEquals($expected, $actual);
    }

    /**
     * Tests that it returns formatted value with up to six decimals from fractional monetary values.
     */
    public function testToFormatCanDisplayUpToSixDecimals()
    {
        $this->loadConfiguration()->loadCountriesServiceProvider();

        $expected = '£0.106544';
        $actual = (new CurrencyHelper($this->app->config))->toFormat('0.106544');

        $this->assertEquals($expected, $actual);
    }
}
