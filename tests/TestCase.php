<?php

namespace PodPoint\I18n\Tests;

use Illuminate\Config\Repository;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Translation\Translator;
use Illuminate\Translation\FileLoader;
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

        $this->app->config = $this->loadConfiguration();

        $this->app->translator = $this->loadTranslation();

        $this->loadServiceProvider();
    }

    /**
     * Loads the package configurations onto the Application Mock.
     *
     * @return Repository
     */
    public function loadConfiguration()
    {
        $filesystem = new Filesystem();

        try {
            return new Repository([
                'countries' => $filesystem->getRequire(__DIR__ . '/../src/config/countries.php'),
                'countries-partial' => $filesystem->getRequire(__DIR__ . '/../src/config/countries-partial.php'),
            ]);
        } catch (FileNotFoundException $e) {
            die("Package configuration files ['src/config/countries', 'src/config/countries-partial'] not found.");
        }
    }

    /**
     * Loads the package translations onto the Application Mock.
     *
     * @return Translator
     */
    public function loadTranslation()
    {
        $loader = new FileLoader(new Filesystem, __DIR__ . '/../src/resources/lang');

        $loader->addNamespace('i18n', __DIR__ . '/../src/resources/lang');

        return new Translator($loader, 'en');
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
