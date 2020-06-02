<?php

namespace PodPoint\I18n\Tests\Unit;

use PodPoint\I18n\CountryCode;
use PodPoint\I18n\CountryHelper;
use PodPoint\I18n\Tests\TestCase;

class CountryHelperTest extends TestCase
{
    /**
     * Tests that the find by method works by trying it with a locale.
     */
    public function testFindByLocale()
    {
        $this->loadConfiguration()->loadCountriesServiceProvider();

        $expected = $this->app->config->get('countries.' . CountryCode::UNITED_KINGDOM);

        $actual = (new CountryHelper($this->app->config))->findBy('locale', 'en');

        $this->assertEquals($expected, $actual);
    }
}
