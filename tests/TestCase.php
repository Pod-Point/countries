<?php

namespace PodPoint\I18n\Tests;

use Illuminate\Config\Repository;
use Illuminate\Filesystem\Filesystem;
use PHPUnit\Framework\MockObject\MockObject;
use Illuminate\Contracts\Foundation\Application;
use PodPoint\I18n\Providers\CountriesServiceProvider;
use Illuminate\Contracts\Filesystem\FileNotFoundException;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    /**
     * Application mock.
     *
     * @var Application|MockObject
     */
    protected $app;

    /**
     * Set up tests.
     */
    public function setUp()
    {
        parent::setUp();

        $this->app = $this->createMock(Application::class);
        $this->app->config = new Repository();
    }

    /**
     * Loads the package configuration onto the Application Mock.
     */
    public function loadConfiguration()
    {
        $filesystem = new Filesystem();

        try {
            $this->app->config = new Repository([
                'countries' => $filesystem->getRequire(__DIR__ . '/../src/config/countries.php'),
                'supported-countries' => $filesystem->getRequire(__DIR__ . '/../src/config/supported-countries.php'),
            ]);
        } catch (FileNotFoundException $e) {
            die("Package configuration files ['src/config/countries', 'src/config/supported-countries'] not found.");
        }

        return $this;
    }

    /**
     * Execute the package service provider and load the final configuration onto the Application Mock.
     */
    public function loadCountriesServiceProvider()
    {
        (new CountriesServiceProvider($this->app))->register();

        return $this;
    }
}
