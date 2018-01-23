<?php

namespace PodPoint\Countries\Tests\ViewComposers;

use PodPoint\Countries\ViewComposers\CountryLocaleViewComposer;
use Illuminate\View\View;
use Illuminate\Config\Repository;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;

class TestCountryLocaleViewComposer extends PHPUnitTestCase
{
    /**
     * Makes sure every methods of the view composer are called properly.
     */
    public function testCompose()
    {
        /** @var Repository|\PHPUnit_Framework_MockObject_MockObject $configMock */
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
                ],
                'GB' => [
                    'name' => 'United Kingdom',
                    'diallingCode' => 44,
                    'locale' => 'en',
                ],
            ]);

        $countryLocaleViewComposer->compose($viewMock);
    }
}
