<?php

namespace PodPoint\Countries\Tests;

use Illuminate\Config\Repository;
use Illuminate\Contracts\Foundation\Application;
use Mockery;
use PodPoint\Countries\Providers\CountriesServiceProvider;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;

class TestCountriesServiceProvider extends PHPUnitTestCase
{
    /**
     * Application mock.
     *
     * @var Mockery
     */
    private $appMock;

    /**
     * Set up tests.
     *
     * @return void
     */
    public function setUp()
    {
        $this->appMock = Mockery::mock(Application::class);
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
        $configMock = Mockery::mock(Repository::class);
        $configMock->shouldReceive('set', require __DIR__ . '/../../src/config/countries.php')->once();
        $configMock->shouldReceive('set', require __DIR__ . '/../../src/config/countries-partial.php')->once();
        $this->appMock->config = $configMock;

        $provider = new CountriesServiceProvider($this->appMock);

        $provider->register();
    }
}
