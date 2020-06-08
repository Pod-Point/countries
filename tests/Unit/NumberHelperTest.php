<?php

namespace PodPoint\I18n\Tests\Unit;

use PodPoint\I18n\NumberHelper;
use PodPoint\I18n\Tests\TestCase;

class NumberHelperTest extends TestCase
{
    /**
     * Data provider for testToFormat.
     *
     * @return array
     */
    public function providerTestToFormat()
    {
        $value = 1500.51254567890;

        return [
            'number in english format' => [
                $value,
                'en',
                '1,500.513',
            ],
            'number in norwegian format' => [
                $value,
                'no',
                '1 500,513',
            ],
        ];
    }

    /**
     * Tests that it returns float values properly formatted according to a given locale.
     *
     * @dataProvider providerTestToFormat
     *
     * @param float $value
     * @param string $locale
     * @param string $expected
     */
    public function testToFormat(float $value, string $locale, string $expected)
    {
        $this->loadConfiguration()->loadServiceProvider();

        $currencyHelper = new NumberHelper($this->app->config);

        $actual = $currencyHelper->toFormat($value, $locale);

        $this->assertEquals($expected, $actual);
    }
}
