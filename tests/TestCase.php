<?php

namespace PodPoint\I18n\Tests;

use Illuminate\Config\Repository;
use Illuminate\Filesystem\Filesystem;
use PHPUnit\Framework\MockObject\MockObject;
use Illuminate\Contracts\Foundation\Application;
use PodPoint\I18n\CountriesServiceProvider;
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
    public function setUp(): void
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
                'countries-partial' => $filesystem->getRequire(__DIR__ . '/../src/config/countries-partial.php'),
            ]);
        } catch (FileNotFoundException $e) {
            die("Package configuration files not found: {$e->getMessage()}");
        }

        return $this;
    }

    /**
     * Execute the package service provider and load the final configuration onto the Application Mock.
     */
    public function loadServiceProvider()
    {
        (new CountriesServiceProvider($this->app))->register();

        return $this;
    }
}
