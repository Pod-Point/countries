<?php

namespace PodPoint\Countries\Tests\Providers;

use Illuminate\Config\Repository;
use Illuminate\Contracts\Foundation\Application;
use PodPoint\Countries\Providers\CountriesServiceProvider;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;

class TestCountriesServiceProvider extends PHPUnitTestCase
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
        $this->appMock = $this->getMockBuilder(Application::class)->getMock();
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
        $configMock = $this->getMockBuilder(Repository::class)->getMock();

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
