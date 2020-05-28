<?php

namespace PodPoint\I18n\Vat\Providers\Vatsense;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use League\Flysystem\Filesystem;
use PodPoint\I18n\Aws\Client;
use PodPoint\I18n\CountryCode;
use PodPoint\I18n\Vat\Providers\Api;
use PodPoint\I18n\Vat\Service;
use PodPoint\I18n\Vat\VatRate;

class Vatsense extends Api implements Service
{
    private $endpoint = 'https://api.vatsense.com/1.0/rates';
    private $apikey;
    /**
     * @var Client
     */
    private $s3Client;
    /**
     * @var Filesystem
     */
    private $filesystem;

    public function __construct(string $apikey)
    {
        parent::__construct();
        $this->apikey = $apikey;
        $this->s3Client = new Client();
        $this->filesystem = $this->s3Client->filesystem(env('I18N_BUCKET'));
    }

    /**
     * Fetches rates from Vatsense, upon failure we fallback to most recent rates stored in s3.
     *
     * @return Collection
     */
    private function fetchRates(): Collection
    {
        $password = base64_encode("user:$this->apikey");
        try {
            $response = $this->client->get($this->endpoint, [
                'headers' => [
                    'Authorization' => "Basic $password"
                ]
            ]);
            $json = \GuzzleHttp\json_decode($response->getBody()->getContents(), true);

            $rates = collect($json['data'])->where('standard','!=', null)->map(function ($rate) {
               return new VatRate($rate['country_code'], $rate['standard']['rate']);
            });

            $this->saveRemoteRates($rates);

            return $rates;
        } catch (\Exception $exception) {
            $handler = $this->filesystem->get('/vat-rates/vatsenseRates.json');

            return collect(\GuzzleHttp\json_decode($handler->read()));
        }
    }

    /**
     * Loads rates from cache or fetches them from remote source.
     *
     * @return Collection
     */
    private function loadRates(): Collection
    {
        if (Cache::has('rates')) {
            return Cache::get('rates');
        }

        return $this->fetchRates();
    }

    /**
     * Persist rates into cache and s3 storage.
     *
     * @param Collection $rates
     */
    private function saveRemoteRates(Collection $rates): void
    {
        Cache::put('rates', $rates, Carbon::now()->addMonth());
        $this->filesystem->put('/vat-rates/vatsenseRates.json', \GuzzleHttp\json_encode($rates));
    }

    /**
     * Returns vat rate for a specific country.
     *
     * @param CountryCode $countryCode
     *
     * @return VatRate
     */
    public function getVatRate(CountryCode $countryCode): VatRate
    {
        $rates = $this->loadRates();

        return $rates->where('countryCode', $countryCode->getValue())->first();
    }
}
