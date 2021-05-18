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
        $this->loadConfiguration()->loadServiceProvider();

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
     * Data provider for test.
     *
     * @return array
     */
    public function providerTestFormatToMinorUnitWhenApplicable()
    {
        return [
            'Pound Sterling happy path' => [
                20,
                CurrencyCode::POUND_STERLING,
                'en',
                '20p',
            ],
            'Pound Sterling negative value' => [
                -20,
                CurrencyCode::POUND_STERLING,
                'en',
                '-20p',
            ],
            'Pound Sterling high value' => [
                20000000,
                CurrencyCode::POUND_STERLING,
                'en',
                '£200,000.00',
            ],
            'Pound Sterling within minor unit end' => [
                75,
                CurrencyCode::POUND_STERLING,
                'en',
                '75p',
            ],
            'European Euro' => [
                20,
                CurrencyCode::EURO,
                'ie',
                '€0.20',
            ],
            'European Euro high value' => [
                20000000,
                CurrencyCode::EURO,
                'ie',
                '€200,000.00',
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
        $this->loadConfiguration()->loadServiceProvider();

        $actual = (new CurrencyHelper($this->app->config))->getSymbol($currencyCode, $locale);

        $this->assertEquals($expected, $actual);
    }

    /**
     * Tests that it returns formatted value fractional monetary values.
     */
    public function testToFormatFromInt()
    {
        $this->loadConfiguration()->loadServiceProvider();

        $expected = '£15.50';
        $actual = (new CurrencyHelper($this->app->config))->toFormatFromInt('1550');

        $this->assertEquals($expected, $actual);
    }

    /**
     * Tests that it returns formatted value with up to six decimals from fractional monetary values.
     */
    public function testToFormatCanDisplayUpToSixDecimals()
    {
        $this->loadConfiguration()->loadServiceProvider();

        $expected = '£0.106544';
        $actual = (new CurrencyHelper($this->app->config))->toFormat('0.106544');

        $this->assertEquals($expected, $actual);
    }

    /**
     * Tests that toStandardFormat returns the correct currency formats.
     */
    public function testToStandardFormat()
    {
        $this->loadConfiguration()->loadServiceProvider();

        $currencyHelper = (new CurrencyHelper($this->app->config));

        $expected = '£54.65';
        $actual = $currencyHelper->toStandardFormat(54.6532);
        $this->assertEquals($expected, $actual);

        $expected = '€140.00';
        $actual = $currencyHelper->toStandardFormat(140, CurrencyCode::EURO, 'ie');
        $this->assertEquals($expected, $actual);

        $nonBreakingSpace = "\xC2\xA0";
        $expected = "kr{$nonBreakingSpace}542,67";
        $actual = $currencyHelper->toStandardFormat(542.668, CurrencyCode::NORWEGIAN_KRONE, 'no');

        $this->assertEquals($expected, $actual);
    }

    /**
     * Tests that toStandardFormat returns the correct currency formats with the specified precision.
     */
    public function testToStandardFormatWithCustomPrecision()
    {
        $this->loadConfiguration()->loadServiceProvider();

        $currencyHelper = (new CurrencyHelper($this->app->config));

        $expected = '£5,167.6543';
        $actual = $currencyHelper->toStandardFormat(
            5167.65428954,
            CurrencyCode::POUND_STERLING,
            'en',
            4
        );
        $this->assertEquals($expected, $actual);

        $expected = '£0.534560';
        $actual = $currencyHelper->toStandardFormat(
            0.53456,
            CurrencyCode::POUND_STERLING,
            'en',
            6
        );
        $this->assertEquals($expected, $actual);

        $expected = '€1,560';
        $actual = $currencyHelper->toStandardFormat(
            1560.34334,
            CurrencyCode::EURO,
            'ie',
            0
        );
        $this->assertEquals($expected, $actual);
    }

    /**
     * Tests that it returns formatted value with minor unit symbol from fractional monetary values.
     *
     * @dataProvider  providerTestFormatToMinorUnitWhenApplicable
     * @param int $value
     * @param string $currencyCode
     * @param string $locale
     * @param string $expected
     */
    public function testFormatToMinorUnitWhenApplicable(int $value, string $currencyCode, string $locale, string $expected)
    {
        $this->loadConfiguration()->loadServiceProvider();

        $actual = (new CurrencyHelper($this->app->config))->formatToMinorUnitWhenApplicable($value, $currencyCode, $locale);

        $this->assertEquals($expected, $actual);
    }
}
