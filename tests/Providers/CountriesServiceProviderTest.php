<?php

namespace PodPoint\I18n\Tests\Providers;

use Illuminate\Config\Repository;
use Illuminate\Contracts\Foundation\Application;
use PHPUnit\Framework\TestCase;
use PodPoint\I18n\Providers\CountriesServiceProvider;

class CountriesServiceProviderTest extends TestCase
{
    /**
     * Application mock.
     *
     * @var Application
     */
    private $appMock;

    /**
     * Set up tests.
     */
    public function setUp()
    {
        $this->appMock = $this->createMock(Application::class);
    }

    /**
     * Tests that the countries service provider can be instantiated.
     */
    public function testCountriesServiceProviderCanBeInstantiated()
    {
        $provider = new CountriesServiceProvider($this->appMock);

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

        $this->appMock->config = $configMock;

        $provider = new CountriesServiceProvider($this->appMock);

        $provider->register();
    }
}
