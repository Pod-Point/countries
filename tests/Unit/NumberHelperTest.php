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
    public function providerTestToFormat(): array
    {
        $nonBreakingSpace = "\xC2\xA0";

        return [
            'number in english format with default precision' => [
                2456.3487283384,
                'en',
                null,
                '2,456.349',
            ],
            'number in english format with fixed precision' => [
                2456.3487283384,
                'en',
                5,
                '2,456.34873',
            ],
            'number in english (IE) format with default precision' => [
                1569.7834745,
                'ie',
                null,
                '1,569.783',
            ],
            'number in english (IE) format with fixed precision' => [
                1569.7834745,
                'ie',
                0,
                '1,570',
            ],
            'number in norwegian format with default precision' => [
                1500.51254567890,
                'no',
                null,
                "1{$nonBreakingSpace}500,513",
            ],
            'number in norwegian format with fixed precision' => [
                1500.51254567890,
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
     * @param  float  $value
     * @param  string  $locale
     * @param  int|null  $precision
     * @param  string  $expected
     */
    public function testToFormat(float $value, string $locale, ?int $precision, string $expected)
    {
        $this->loadConfiguration()->loadServiceProvider();

        $numberHelper = new NumberHelper($this->app->config);

        $actual = $numberHelper->toFormat($value, $locale, $precision);

        $this->assertSame($expected, $actual);
    }
}
