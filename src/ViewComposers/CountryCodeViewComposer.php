<?php

namespace PodPoint\I18n\ViewComposers;

use Illuminate\View\View;

class CountryCodeViewComposer
{
    /**
     * Binds the data to the view.
     *
     * @param View $view
     */
    public function compose(View $view)
    {
        $view->with(['countryCodeOptions' => $this->countryCodeOptions()]);
    }

    /**
     * Get the country-code options by looping the countries in the config, while also adding the flag emojis.
     *
     * @throws \InvalidArgumentException
     *
     * @return string
     */
    private function countryCodeOptions()
    {
        $countries = config('countries');
        $choices = [];
        foreach ($countries as $countryCode => $country) {
            $choices[] = $this->countryChoice($countryCode, $country);
        }

        $defaultCountries = [
            'GB' => $countries['GB'],
        ];
        $defaultChoices = [];
        foreach ($defaultCountries as $countryCode => $country) {
            $defaultChoices[] = $this->countryChoice($countryCode, $country, true);
        }

        $countryGroups = [
            [
                'id' => 1,
                'label' => '',
                'choices' => $defaultChoices,
            ],
            [
                'id' => 2,
                'label' => '',
                'choices' => $choices,
            ],
        ];

        return \GuzzleHttp\json_encode($countryGroups);
    }

    /**
     * Get a country choice.
     *
     * @param string $countryCode
     * @param array  $country
     * @param bool   $defaultChoice
     *
     * @return array
     */
    public function countryChoice(string $countryCode, array $country, bool $defaultChoice = false)
    {
        return [
            'value' => $countryCode,
            'label' => $this->countryLabel($countryCode, $country['name'], $country['diallingCode'], $defaultChoice),
            'customProperties' => [
                'description' => $country['name'],
            ],
        ];
    }

    /**
     * Get a country label.
     *
     * @param string   $countryCode
     * @param string   $countryName
     * @param int|null $diallingCode
     * @param bool     $defaultChoice
     *
     * @return string
     */
    private function countryLabel(string $countryCode, string $countryName, $diallingCode, bool $defaultChoice = false)
    {
        return $this->emojiFlag($countryCode) . $this->countryNameMarkup($countryName, $defaultChoice) . $this->dialingCodeMarkup($diallingCode, $defaultChoice);
    }

    /**
     * Get a country name markup.
     *
     * @param string $name
     * @param bool   $defaultChoice
     *
     * @return string
     */
    private function countryNameMarkup(string $name, bool $defaultChoice = false)
    {
        return '&nbsp;<span class="country-name ' . ($defaultChoice ? 'country-name--heading' : '') . '">' . $name . '</span>';
    }

    /**
     * Get a dialling code markup.
     *
     * @param int|null $diallingCode
     * @param bool     $defaultChoice
     *
     * @return string
     */
    private function dialingCodeMarkup($diallingCode, bool $defaultChoice = false)
    {
        if (is_null($diallingCode)) {
            return '';
        }

        return '&nbsp;<span class="country-dialling-code ' . ($defaultChoice ? 'country-dialling-code--heading' : '') . '">(+' . $diallingCode . ')</span>';
    }

    /**
     * Get the emoji flag for a given country code.
     *
     * @param string $countryCode
     *
     * @return string
     */
    private function emojiFlag(string $countryCode)
    {
        $letters = str_split($countryCode);

        $character = '';
        foreach ($letters as $letter) {
            $character .= $this->unicodeCharacter($letter);
        }

        return $character;
    }

    /**
     * Get the unicode character for a given letter.
     *
     * @param string $letter
     *
     * @return string
     */
    private function unicodeCharacter(string $letter)
    {
        $codes = [
            'a' => '1F1E6',
            'b' => '1F1E7',
            'c' => '1F1E8',
            'd' => '1F1E9',
            'e' => '1F1EA',
            'f' => '1F1EB',
            'g' => '1F1EC',
            'h' => '1F1ED',
            'i' => '1F1EE',
            'j' => '1F1EF',
            'k' => '1F1F0',
            'l' => '1F1F1',
            'm' => '1F1F2',
            'n' => '1F1F3',
            'o' => '1F1F4',
            'p' => '1F1F5',
            'q' => '1F1F6',
            'r' => '1F1F7',
            's' => '1F1F8',
            't' => '1F1F9',
            'u' => '1F1FA',
            'v' => '1F1FB',
            'w' => '1F1FC',
            'x' => '1F1FD',
            'y' => '1F1FE',
            'z' => '1F1FF',
        ];

        $letter = strtolower($letter);
        if (array_key_exists($letter, $codes)) {
            return mb_convert_encoding('&#x' . $codes[$letter] . ';', 'UTF-8', 'HTML-ENTITIES');
        }

        return '';
    }
}
