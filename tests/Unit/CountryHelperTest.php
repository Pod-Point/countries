<?php

namespace PodPoint\I18n\Tests\Unit;

use PodPoint\I18n\CountryHelper;
use PodPoint\I18n\Tests\TestCase;

class CountryHelperTest extends TestCase
{
    /**
     * Tests that the findBy method returns the first matching result using an existing country
     * definition attribute.
     */
    public function testFindByLocaleCanLookForCountryDefinition()
    {
        $this->app->config->set('countries.FOO', $expected = ['foo' => 'bar', 'expected' => true]);
        $this->app->config->set('countries.BAR', ['bar' => 'foo', 'expected' => false]);
        $this->app->config->set('countries.BAZ', ['foo' => 'bar', 'expected' => false]);

        $actual = (new CountryHelper($this->app->config))->findBy('foo', 'bar');

        $this->assertEquals($expected, $actual);
    }

    /**
     * Tests that the findBy method returns null if nothing matches.
     */
    public function testFindByLocaleCanReturnNullIfNotFound()
    {
        $this->app->config->set('countries.FOO', ['foo' => 'bar']);
        $this->app->config->set('countries.BAR', ['bar' => 'foo']);

        $actual = (new CountryHelper($this->app->config))->findBy('baz', 'foo');

        $this->assertNull($actual);
    }
}
