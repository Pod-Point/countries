<?php

namespace PodPoint\I18n\Tests;

use Illuminate\Config\Repository;
use PHPUnit\Framework\TestCase;
use PodPoint\I18n\CurrencyCode;
use PodPoint\I18n\CurrencyHelper;

class TestCurrencyHelper extends TestCase
{
    /**
     * Tests that the find by method works by trying it with a locale.
     */
    public function testToFormat()
    {
        $config = $this->createMock(Repository::class);
        $currencyHelper = new CurrencyHelper($config);
        $countries = require __DIR__ . '/../src/config/countries.php';

        $config->expects($this->once())
            ->method('get')
            ->with('countries')
            ->willReturn($countries);

        $actual = $currencyHelper->toFormat(
            1500.5,
            'en',
            CurrencyCode::POUND_STERLING
        );

        $expected = '£1,500.50';

        $this->assertEquals($expected, $actual);
    }
}
