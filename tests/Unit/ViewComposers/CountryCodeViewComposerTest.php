<?php

namespace PodPoint\I18n\Tests\Unit\ViewComposers;

use Illuminate\View\View;
use Illuminate\Config\Repository;
use PodPoint\I18n\Tests\TestCase;
use PodPoint\I18n\ViewComposers\CountryCodeViewComposer;

class CountryCodeViewComposerTest extends TestCase
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

        $countryLocaleViewComposer = new CountryCodeViewComposer($configMock);

        /** @var View|\PHPUnit_Framework_MockObject_MockObject $viewMock */
        $viewMock = $this->getMockBuilder(View::class)
            ->disableOriginalConstructor()
            ->setMethods(['with'])
            ->getMock();

        $viewMock->expects($this->once())
            ->method('with');

        $configMock->expects($this->once())
            ->method('get')
            ->with('countries')
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

    /**
     * Tests countryChoice() returns an array properly formatted when country is not the default choice.
     */
    public function testCountryChoiceReturnsAnArrayProperlyFormattedWhenNotDefaultChoice()
    {
        $countryLocaleViewComposer = new CountryCodeViewComposer($this->app->config);

        $actual = $countryLocaleViewComposer->countryChoice('GB', [
            'name' => 'United Kingdom',
            'diallingCode' => 44,
            'locale' => 'en',
            'language' => 'ENG',
        ]);

        $expected = [
            "value" => "GB",
            "label" => '🇬🇧&nbsp;<span class="country-name">United Kingdom</span>&nbsp;<span class="country-dialling-code">(+44)</span>',
            "customProperties" => [
                "description" => "United Kingdom",
            ],
        ];

        $this->assertEquals($expected, $actual);
    }

    /**
     * Tests countryChoice() returns an array properly formatted when country is the default choice.
     */
    public function testCountryChoiceReturnsAnArrayProperlyFormattedWhenDefaultChoice()
    {
        $countryLocaleViewComposer = new CountryCodeViewComposer($this->app->config);

        $actual = $countryLocaleViewComposer->countryChoice('GB', [
            'name' => 'United Kingdom',
            'diallingCode' => 44,
            'locale' => 'en',
            'language' => 'ENG',
        ], true);

        $expected = [
            "value" => "GB",
            "label" => '🇬🇧&nbsp;<span class="country-name country-name--heading">United Kingdom</span>&nbsp;<span class="country-dialling-code country-dialling-code--heading">(+44)</span>',
            "customProperties" => [
                "description" => "United Kingdom",
            ],
        ];

        $this->assertEquals($expected, $actual);
    }
}
