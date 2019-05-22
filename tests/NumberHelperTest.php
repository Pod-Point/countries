<?php

namespace PodPoint\I18n\Tests;

use Illuminate\Config\Repository;
use PHPUnit\Framework\TestCase;
use PodPoint\I18n\NumberHelper;

class NumberHelperTest extends TestCase
{
    /**
     * Data provider for testToFormat.
     *
     * @return array
     */
    public function providerTestFormat()
    {
        $value = 1500.5;

        return [
            'number in english format' => [
                $value,
                'en',
                '1,500.5',
            ],
            'number in norwegian format' => [
                $value,
                'no',
                '1 500,5',
            ],
        ];
    }

    /**
     * Tests that it returns float values properly formatted according to locale and currency.
     *
     * @dataProvider providerTestFormat
     *
     * @param float $value
     * @param string $locale
     * @param string $expected
     */
    public function testFormat(float $value, string $locale, string $expected)
    {
        $config = $this->createMock(Repository::class);
        $currencyHelper = new NumberHelper($config);
        $countries = require __DIR__ . '/../src/config/countries.php';

        $config->expects($this->once())
            ->method('get')
            ->with('countries')
            ->willReturn($countries);

        $actual = $currencyHelper->format(
            $value,
            $locale
        );

        $this->assertEquals($expected, $actual);
    }
}
