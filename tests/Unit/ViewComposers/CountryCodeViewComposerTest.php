<?php

namespace PodPoint\I18n\Tests\Unit\ViewComposers;

use Illuminate\View\View;
use PodPoint\I18n\Tests\TestCase;
use PodPoint\I18n\ViewComposers\CountryCodeViewComposer;

class CountryCodeViewComposerTest extends TestCase
{
    /**
     * Makes sure every methods of the view composer are called properly.
     */
    public function testCompose()
    {
        $this->loadConfiguration()->loadServiceProvider();

        $countryLocaleViewComposer = new CountryCodeViewComposer($this->app->config);

        /** @var View|\PHPUnit_Framework_MockObject_MockObject $viewMock */
        $viewMock = $this->getMockBuilder(View::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['with'])
            ->getMock();

        $viewMock->expects($this->once())
            ->method('with')
            ->with($this->callback(function ($array) {
                return $this->isJson()->evaluate(
                    $array['countryCodeOptions'],
                    'countryCodeOptions returned by CountryCodeViewComposer is not a valid json string.',
                    true
                );
            }));

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
            'value' => 'GB',
            'label' => '🇬🇧&nbsp;<span class="country-name">United Kingdom</span>&nbsp;<span class="country-dialling-code">(+44)</span>',
            'customProperties' => [
                'description' => 'United Kingdom',
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
            'value' => 'GB',
            'label' => '🇬🇧&nbsp;<span class="country-name country-name--heading">United Kingdom</span>&nbsp;<span class="country-dialling-code country-dialling-code--heading">(+44)</span>',
            'customProperties' => [
                'description' => 'United Kingdom',
            ],
        ];

        $this->assertEquals($expected, $actual);
    }
}
