<?php

namespace PodPoint\I18n\Vat\Providers\Vatsense;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use PodPoint\I18n\CountryCode;
use PodPoint\I18n\Vat\Providers\Api;
use PodPoint\I18n\Vat\Service;
use PodPoint\I18n\Vat\VatRate;

class Vatsense extends Api implements Service
{
    private $endpoint = 'https://api.vatsense.com/1.0/rates';
    private $apikey = 'f150fe208eaf2ca05b028e2160564784';

    /**
     * @var Collection
     */
    private $rates;

    public function __construct()
    {
        parent::__construct();

        $this->getRates();
    }

    private function getRates(): void
    {
        if (Cache::has('rates')) {
            $this->rates = Cache::get('rates');

            return;
        }

        $password = base64_encode("user:$this->apikey");
        try {
            $response = $this->client->get($this->endpoint, [
                'headers' => [
                    'Authorization' => "Basic $password"
                ]
            ]);
            $json = \GuzzleHttp\json_decode($response->getBody()->getContents(), true);

            $this->rates = collect($json['data'])->where('standard','!=', null)->map(function ($rate) {
               return new VatRate($rate['country_code'], $rate['standard']['rate']);
            });
            Cache::put('rates', $this->rates, Carbon::now()->addMonths(1));
        } catch (\Exception $exception) {
            // todo we need logging
        }
    }

    public function getVatRate(CountryCode $countryCode): ?VatRate
    {
        return $this->rates->where('countryCode', $countryCode->getValue())->first();
    }
}
