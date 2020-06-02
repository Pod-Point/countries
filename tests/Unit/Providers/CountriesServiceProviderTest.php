<?php

namespace PodPoint\I18n\Tests\Unit\Providers;

use PodPoint\I18n\CountryCode;
use Illuminate\Config\Repository;
use PodPoint\I18n\Tests\TestCase;
use PodPoint\I18n\Providers\CountriesServiceProvider;

class CountriesServiceProviderTest extends TestCase
{
    /**
     * Tests that the countries service provider can be instantiated.
     */
    public function testCountriesServiceProviderCanBeInstantiated()
    {
        $provider = new CountriesServiceProvider($this->app);

        $this->assertInstanceOf(CountriesServiceProvider::class, $provider);
    }

    /**
     * Tests that countries config gets loaded into the application.
     */
    public function testCountriesConfigCanBeRegistered()
    {
        $configMock = $this->createMock(Repository::class);

        $configMock->expects($this->at(0))
            ->method('set')
            ->with('countries');

        $configMock->expects($this->at(1))
            ->method('set')
            ->with('countries-partial');

        $this->app->config = $configMock;

        $this->loadCountriesServiceProvider();
    }

    /**
     * Make sure the additional info from `league/iso3166` package is automatically merged and
     * loaded with our `countries` configuration file as well as our miscellaneous bespoke
     * Laravel data from `countries-partial` config. Both should have the same data in
     * the end, `countries-partial` will just but a filtered version with only the
     * supported countries.
     */
    public function testCountriesConfigIsEnhancedWithAdditionalInfo()
    {
        $this->loadConfiguration();

        $countryWithBasicInfo = $this->app->config->get('countries.' . CountryCode::UNITED_KINGDOM);

        $this->assertArrayHasKey('name', $countryWithBasicInfo);
        $this->assertArrayHasKey('diallingCode', $countryWithBasicInfo);
        $this->assertArrayNotHasKey('systemLocale', $countryWithBasicInfo);
        $this->assertArrayNotHasKey('locale', $countryWithBasicInfo);
        $this->assertArrayNotHasKey('language', $countryWithBasicInfo);
        $this->assertArrayNotHasKey('tld', $countryWithBasicInfo);
        $this->assertArrayNotHasKey('timezone', $countryWithBasicInfo);
        $this->assertArrayNotHasKey('alpha2', $countryWithBasicInfo);
        $this->assertArrayNotHasKey('alpha3', $countryWithBasicInfo);
        $this->assertArrayNotHasKey('numeric', $countryWithBasicInfo);
        $this->assertArrayNotHasKey('currency', $countryWithBasicInfo);

        $this->loadCountriesServiceProvider();

        collect([
            $this->app->config->get('countries.' . CountryCode::UNITED_KINGDOM),
            $this->app->config->get('countries-partial.' . CountryCode::UNITED_KINGDOM),
        ])->each(function ($enhancedCountry) {
            $this->assertArrayHasKey('name', $enhancedCountry);
            $this->assertArrayHasKey('diallingCode', $enhancedCountry);
            $this->assertArrayHasKey('systemLocale', $enhancedCountry);
            $this->assertArrayHasKey('locale', $enhancedCountry);
            $this->assertArrayHasKey('language', $enhancedCountry);
            $this->assertArrayHasKey('tld', $enhancedCountry);
            $this->assertArrayHasKey('timezone', $enhancedCountry);
            $this->assertArrayHasKey('alpha2', $enhancedCountry);
            $this->assertArrayHasKey('alpha3', $enhancedCountry);
            $this->assertArrayHasKey('numeric', $enhancedCountry);
            $this->assertArrayHasKey('currency', $enhancedCountry);
        });
    }

    /**
     * Make sure the additional info from 'league/iso3166' package is automatically
     * merged and loaded with our 'countries' configuration file.
     */
    public function testPartialCountriesConfigHasOnlySupportedCountriesAndLocales()
    {
        $this->loadConfiguration()->loadCountriesServiceProvider();

        $countryCodes = array_keys($this->app->config->get('countries-partial'));

        $this->assertEquals([
             CountryCode::UNITED_KINGDOM,
             CountryCode::IRELAND,
             CountryCode::NORWAY,
        ], $countryCodes);

        collect($this->app->config->get('countries-partial'))->each(function ($country) {
            $supportedLocales = ['en', 'no'];

            if (isset($country['locale'])) {
                $this->assertTrue(in_array($country['locale'], $supportedLocales));
            }
        });
    }
}