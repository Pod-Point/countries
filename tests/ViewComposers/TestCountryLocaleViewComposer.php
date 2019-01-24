<?php

namespace PodPoint\I18n\Tests\ViewComposers;

use PHPUnit\Framework\TestCase;
use PodPoint\I18n\ViewComposers\CountryLocaleViewComposer;
use Illuminate\View\View;
use Illuminate\Config\Repository;

class TestCountryLocaleViewComposer extends TestCase
{
    /**
     * Makes sure every methods of the view composer are called properly.
     */
    public function testCompose()
    {
        /** @var Repository|\PHPUnit\Framework\MockObject\MockObject $configMock */
        $configMock = $this->getMockBuilder(Repository::class)
            ->disableOriginalConstructor()
            ->setMethods(['get'])
            ->getMock();

        $countryLocaleViewComposer = new CountryLocaleViewComposer($configMock);

        /** @var View|\PHPUnit_Framework_MockObject_MockObject $viewMock */
        $viewMock = $this->getMockBuilder(View::class)
            ->disableOriginalConstructor()
            ->setMethods(['with'])
            ->getMock();

        $viewMock->expects($this->once())
            ->method('with');

        $configMock->expects($this->once())
            ->method('get')
            ->willReturn([
                'NO' => [
                    'name' => 'Norway',
                    'diallingCode' => 47,
                    'locale' => 'no',
                    'language' => 'NOR',
                ],
                'GB' => [
                    'name' => 'United Kingdom',
                    'diallingCode' => 44,
                    'locale' => 'en',
                    'language' => 'ENG',
                ],
            ]);

        $countryLocaleViewComposer->compose($viewMock);
    }
}
