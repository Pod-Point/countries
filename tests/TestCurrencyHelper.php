<?php

namespace PodPoint\I18n\Tests;

use Illuminate\Config\Repository;
use PHPUnit\Framework\TestCase;
use PodPoint\I18n\CurrencyCode;
use PodPoint\I18n\CurrencyHelper;

class TestCurrencyHelper extends TestCase
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
                'en',
                CurrencyCode::POUND_STERLING,
                '£1,500.50',
            ],
            'pound sterling in norwegian' => [
                $value,
                'no',
                CurrencyCode::POUND_STERLING,
                '£ 1 500,50',
            ],
        ];
    }

    /**
     * Tests that to Format returns values properly formatted according to locale and currency.
     *
     * @dataProvider providerTestToFormat
     *
     * @param float $value
     * @param string $locale
     * @param string $currencyCode
     * @param string $expected
     */
    public function testToFormat(float $value, string $locale, string $currencyCode, string $expected)
    {
        $config = $this->createMock(Repository::class);
        $currencyHelper = new CurrencyHelper($config);
        $countries = require __DIR__ . '/../src/config/countries.php';

        $config->expects($this->once())
            ->method('get')
            ->with('countries')
            ->willReturn($countries);

        $actual = $currencyHelper->toFormat(
            $value,
            $locale,
            $currencyCode
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
     * Tests that get symbol return the currency symbol from locale.
     *
     * @dataProvider providerTestGetSymbol
     *
     * @param string $currencyCode
     * @param string $locale
     * @param string $expected
     */
    public function testGetSymbol(string $currencyCode, string $locale, string $expected)
    {
        $config = $this->createMock(Repository::class);
        $currencyHelper = new CurrencyHelper($config);
        $countries = require __DIR__. '/../src/config/countries.php';

        $config->expects($this->once())
            ->method('get')
            ->with('countries')
            ->willReturn($countries);

        $actual = $currencyHelper->getSymbol($currencyCode, $locale);

        $this->assertEquals($expected, $actual);
    }
}
