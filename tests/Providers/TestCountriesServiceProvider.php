<?php

use Illuminate\Config\Repository;
use Mockery as m;
use PodPoint\Countries\Providers\CountriesServiceProvider;
use Stubs\ApplicationStub;

class TestCountriesServiceProvider extends PHPUnit_Framework_TestCase
{
    /**
     * Laravel application stub.
     *
     * @var ApplicationStub
     */
    private $app;

    /**
     * Set up mocks.
     *
     * @return void
     */
    public function setUp()
    {
        $this->app = new ApplicationStub();
    }

    /**
     * Tests that the countries service provider can be instantiated.
     *
     * @return void
     */
    public function testCountriesServiceProviderCanBeInstantiated()
    {
        $provider = new CountriesServiceProvider($this->app);

        $this->assertInstanceOf(CountriesServiceProvider::class, $provider);
    }

    /**
     * Tests that countries config gets loaded into the application.
     *
     * @return void
     */
    public function testCountriesConfigCanBeRegistered()
    {
        $this->app['config'] = m::mock(Repository::class);
        $this->app['config']->shouldReceive('set', require __DIR__ . '/../../src/config/countries.php')->once();
        $this->app['config']->shouldReceive('set', require __DIR__ . '/../../src/config/countries-partial.php')->once();

        $provider = new CountriesServiceProvider($this->app);

        $provider->register();
    }
}
