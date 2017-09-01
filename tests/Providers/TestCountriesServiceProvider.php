<?php

namespace PodPoint\Countries\Tests;

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
     *
     * @return void
     */
    public function setUp()
    {
        $this->appMock = $this->getMockBuilder(Application::class)->getMock();
    }

    /**
     * Tests that the countries service provider can be instantiated.
     *
     * @return void
     */
    public function testCountriesServiceProviderCanBeInstantiated()
    {
        $provider = new CountriesServiceProvider($this->appMock);

        $this->assertInstanceOf(CountriesServiceProvider::class, $provider);
    }

    /**
     * Tests that countries config gets loaded into the application.
     *
     * @return void
     */
    public function testCountriesConfigCanBeRegistered()
    {
        $configMock = $this->getMockBuilder(Repository::class)->getMock();
        $configMock->expects($this->at(0))->method('set')->with('countries', require __DIR__ . '/../../src/config/countries.php');
        $configMock->expects($this->at(1))->method('set')->with('countries-partial', require __DIR__ . '/../../src/config/countries-partial.php');

        $this->appMock->config = $configMock;

        $provider = new CountriesServiceProvider($this->appMock);

        $provider->register();
    }
}
