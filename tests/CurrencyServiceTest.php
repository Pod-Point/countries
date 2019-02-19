<?php

namespace PodPoint\I18n\Tests;

use Carbon\Carbon;
use GuzzleHttp\Psr7\Response;
use Illuminate\Contracts\Cache\Repository as Cache;
use Illuminate\Contracts\Config\Repository as Config;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use PodPoint\I18n\Currency\ExchangeRate;
use PodPoint\I18n\Currency\Cache\Service as CacheService;
use PodPoint\I18n\Currency\OpenExchangeRates\Client;
use PodPoint\I18n\Currency\OpenExchangeRates\Service;

class CurrencyServiceTest extends TestCase
{
    /**
     * @var Cache|MockObject
     */
    private $mockCache;

    /**
     * @var Config|MockObject
     */
    private $mockConfig;

    /**
     * @var Client|MockObject
     */
    private $mockClient;

    /**
     * @var Service|MockObject
     */
    private $mockService;

    /**
     * @var Service
     */
    private $service;

    /**
     * @var CacheService
     */
    private $cachedService;

    /**
     * Creates mocked cache, config and OpenExchangeRates client & service instances.
     */
    public function setUp()
    {
        parent::setUp();

        $this->mockCache = $this->createMock(Cache::class);
        $this->mockConfig = $this->createMock(Config::class);
        $this->mockClient = $this->createMock(Client::class);
        $this->mockService = $this->createMock(Service::class);

        $this->service = new Service($this->mockConfig, $this->mockClient);
        $this->cachedService = new CacheService($this->mockCache, $this->mockService);
    }

    /**
     * Tests getting the latest conversion rates from the OpenExchangeRates API.
     */
    public function testGettingLatestRates()
    {
        $base = 'GBP';
        $currencies = ['NOK'];
        $timestamp = Carbon::now()->startOfSecond();
        $appId = 'someAppId';
        $rate = 11.05;

        $query = http_build_query([
            'app_id' => $appId,
            'base' => $base,
            'symbols' => implode(',', $currencies),
        ]);

        $this->mockConfig->expects($this->once())
            ->method('get')
            ->with('services.oxr.appId')
            ->willReturn($appId);

        $this->mockClient->expects($this->once())
            ->method('__call')
            ->with('get', [
                "latest.json?{$query}",
            ])
            ->willReturn(new Response(200, [], \GuzzleHttp\json_encode([
                'timestamp' => $timestamp->timestamp,
                'rates' => [
                    'NOK' => $rate,
                ],
            ])));

        $expected = collect([
            new ExchangeRate([
                'base' => $base,
                'currency' => 'NOK',
                'rate' => $rate,
                'timestamp' => $timestamp,
            ]),
        ]);

        $results = $this->service->getExchangeRates($base, $currencies, $timestamp);

        $this->assertEquals($expected, $results);
    }

    /**
     * Tests getting historic conversion rates from the OpenExchangeRates API.
     */
    public function testGettingHistoricRates()
    {
        $base = 'GBP';
        $currencies = ['NOK'];
        $timestamp = Carbon::yesterday()->startOfSecond();
        $appId = 'someAppId';
        $rate = 11.05;

        $query = http_build_query([
            'app_id' => $appId,
            'base' => $base,
            'symbols' => implode(',', $currencies),
        ]);

        $this->mockConfig->expects($this->once())
            ->method('get')
            ->with('services.oxr.appId')
            ->willReturn($appId);

        $this->mockClient->expects($this->once())
            ->method('__call')
            ->with('get', [
                "historical/{$timestamp->format('Y-m-d')}.json?{$query}",
            ])
            ->willReturn(new Response(200, [], \GuzzleHttp\json_encode([
                'timestamp' => $timestamp->timestamp,
                'rates' => [
                    'NOK' => $rate,
                ],
            ])));

        $expected = collect([
            new ExchangeRate([
                'base' => $base,
                'currency' => 'NOK',
                'rate' => $rate,
                'timestamp' => $timestamp,
            ]),
        ]);

        $results = $this->service->getExchangeRates($base, $currencies, $timestamp);

        $this->assertEquals($expected, $results);
    }

    /**
     * Tests getting the conversion rates from another service and then storing them in cache.
     */
    public function testSettingCachedRates()
    {
        $base = 'GBP';
        $currencies = ['NOK'];
        $timestamp = Carbon::now()->startOfSecond();
        $rate = 11.05;

        $this->mockCache->expects($this->any())
            ->method('has')
            ->willReturn(false);

        $expected = collect([
            new ExchangeRate([
                'base' => $base,
                'currency' => 'NOK',
                'rate' => $rate,
                'timestamp' => $timestamp,
            ]),
        ]);

        $this->mockService->expects($this->once())
            ->method('getExchangeRates')
            ->with($base, $currencies, $timestamp)
            ->willReturn($expected);

        $this->mockCache->expects($this->once())
            ->method('forever')
            ->with("rates.{$base}.NOK.{$timestamp->format('Y-m-d')}.{$timestamp->hour}", $rate);

        $results = $this->cachedService->getExchangeRates($base, $currencies, $timestamp);

        $this->assertEquals($expected, $results);
    }

    /**
     * Tests getting the conversion rates from the cache.
     */
    public function testGettingCachedRates()
    {
        $base = 'GBP';
        $currencies = ['NOK'];
        $timestamp = Carbon::now()->startOfSecond();
        $rate = 11.05;

        $this->mockCache->expects($this->any())
            ->method('has')
            ->willReturn(true);

        $this->mockCache->expects($this->once())
            ->method('get')
            ->with("rates.{$base}.NOK.{$timestamp->format('Y-m-d')}.{$timestamp->hour}")
            ->willReturn($rate);

        $results = $this->cachedService->getExchangeRates($base, $currencies, $timestamp);

        $expected = collect([
            new ExchangeRate([
                'base' => $base,
                'currency' => 'NOK',
                'rate' => $rate,
                'timestamp' => $timestamp,
            ]),
        ]);

        $this->assertEquals($expected, $results);
    }
}
