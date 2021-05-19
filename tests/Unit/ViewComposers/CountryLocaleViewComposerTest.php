<?php

namespace PodPoint\I18n\Tests\Unit\ViewComposers;

use Illuminate\View\View;
use PodPoint\I18n\Tests\TestCase;
use PodPoint\I18n\ViewComposers\CountryLocaleViewComposer;

class CountryLocaleViewComposerTest extends TestCase
{
    /**
     * Makes sure CountryLocaleViewComposer returns the supported languages/locales options within
     * the `countryLocalesOptions` view variable.
     */
    public function testCompose()
    {
        $this->loadConfiguration()->loadServiceProvider();

        $countryLocaleViewComposer = new CountryLocaleViewComposer($this->app->config);

        /** @var View|\PHPUnit_Framework_MockObject_MockObject $viewMock */
        $viewMock = $this->getMockBuilder(View::class)
            ->disableOriginalConstructor()
            ->setMethods(['with'])
            ->getMock();

        $viewMock->expects($this->once())
            ->method('with')
            ->with([
                'countryLocalesOptions' => [
                    'en' => 'ENG',
                    'no' => 'NOR',
                    'ie' => 'ENG',
                ],
            ]);

        $countryLocaleViewComposer->compose($viewMock);
    }
}
