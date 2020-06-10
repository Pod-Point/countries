<?php

namespace PodPoint\I18n\Tests\Unit;

use PodPoint\I18n\Language;
use PodPoint\I18n\Tests\TestCase;

class LanguageTest extends TestCase
{
    /**
     * @var Language
     */
    private $language;

    /**
     * @inheritDoc
     */
    public function setUp()
    {
        parent::setUp();

        $this->language = new Language($this->app->config, $this->app->translator);
    }

    /**
     * Tests we can get all supported languages index by their locale.
     */
    public function testWeCanGetAllSupportedLanguagesIndexByTheirLocale()
    {
        $this->assertEquals([
            'en' => 'English',
            'no' => 'Norwegian',
        ], $this->language->all()->toArray());
   }

    /**
     * Tests we can filter out one particular language from all supported ones.
     */
    public function testWeCanFilterOutOneParticularLanguageFromAllSupportedOnes()
    {
        $this->assertEquals([
            'en' => 'English',
        ], $this->language->except('no')->toArray());
    }
}
