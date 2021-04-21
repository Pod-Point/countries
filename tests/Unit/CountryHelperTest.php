<?php

namespace PodPoint\I18n\Tests\Unit;

use PodPoint\I18n\CountryHelper;
use PodPoint\I18n\Tests\TestCase;

class CountryHelperTest extends TestCase
{
    /**
     * Tests that the findBy method returns the first matching result using an existing country
     * locale.
     */
    public function testFindByLocaleCanLookForCountryDefinition()
    {
        $this->app->config->set('countries.FOO', $expected = ['locale' => 'en', 'expected' => true]);
        $this->app->config->set('countries.BAR', ['bar' => 'foo', 'expected' => false]);
        $this->app->config->set('countries.BAZ', ['foo' => 'bar', 'expected' => false]);

        $actual = (new CountryHelper($this->app->config))->findByLocale('en');

        $this->assertEquals($expected, $actual);
    }

    /**
     * Tests that the findBy method returns null if nothing matches.
     */
    public function testFindByCanReturnNullIfNotFound()
    {
        $this->app->config->set('countries.FOO', ['foo' => 'bar']);
        $this->app->config->set('countries.BAR', ['bar' => 'foo']);

        $actual = (new CountryHelper($this->app->config))->findBy('baz', 'foo');

        $this->assertNull($actual);
    }

    /**
     * Tests that the getCountryCodeFromLocale method returns the correct country code.
     */
    public function testGetCountryCodeFromLocale()
    {
        $this->app->config->set('countries.GB', ['locale' => 'en']);
        $this->app->config->set('countries.FR', ['locale' => 'fr']);

        $actual = (new CountryHelper($this->app->config))->getCountryCodeFromLocale('en');

        $this->assertEquals('GB', $actual);
    }

    /**
     * Tests that the getCountryCodeFromLocale method returns null if nothing matches.
     */
    public function testGetCountryCodeFromLocaleIfNotFound()
    {
        $this->app->config->set('countries.GB', ['locale' => 'en']);
        $this->app->config->set('countries.FR', ['locale' => 'fr']);

        $actual = (new CountryHelper($this->app->config))->getCountryCodeFromLocale('it');

        $this->assertNull($actual);
    }
}
