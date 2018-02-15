<?php

namespace PodPoint\Countries\Tests;

use Illuminate\Config\Repository;
use PHPUnit\Framework\TestCase;
use PodPoint\Countries\CountryCode;
use PodPoint\Countries\CountryHelper;

class TestCountryHelper extends TestCase
{
    /**
     * Tests that the find by method works by trying it with a locale.
     */
    public function testFindByLocale()
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject|Repository $config */
        $config = $this->getMock(Repository::class);
        $countryHelper = new CountryHelper($config);
        $countries = require __DIR__ . '/../src/config/countries-partial.php';

        $config->expects($this->once())
            ->method('get')
            ->with('countries')
            ->willReturn($countries);

        $expected = array_get($countries, CountryCode::UNITED_KINGDOM);
        $actual = $countryHelper->findBy('locale', 'en');

        $this->assertEquals($expected, $actual);
    }
}
