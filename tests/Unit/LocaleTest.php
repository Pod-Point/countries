<?php

namespace PodPoint\I18n\Tests\Unit;

use PodPoint\I18n\Locale;
use PodPoint\I18n\Tests\TestCase;

class LocaleTest extends TestCase
{
    /**
     * @var Locale
     */
    private $locale;

    /**
     * @inheritDoc
     */
    public function setUp()
    {
        parent::setUp();

        $this->loadConfiguration()->loadServiceProvider();

        $this->locale = new Locale($this->app->config);
    }

    /**
     * Tests we can get all supported languages index by their locale.
     */
    public function testWeCanGetAllSupportedLanguagesIndexByTheirLocale()
    {
        $this->assertEquals([
            'en' => 'ENG',
            'no' => 'NOR',
        ], $this->locale->all());
   }
}
