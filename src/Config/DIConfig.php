<?php

use App\Collections\TransactionsCollection;
use App\Factories\TransactionCollectionFactory;
use App\Providers\Bin\BinList\BinListProvider;
use App\Providers\Bin\BinList\Clients\BinListClient;
use App\Providers\Bin\Local\LocalBinTestProvider;
use App\Providers\Rate\ExchangeRate\Clients\ExchangeRateClient;
use App\Providers\Rate\ExchangeRate\ExchangeRateProvider;
use App\Services\Bin\BaseFetchBinCountryService;
use App\Services\Cache\FileCacheService;
use App\Services\CalculateTransactionsService;

use App\Services\Rate\BaseFetchRateService;

use function DI\create;

return [
    //Exchange Provider implementation
    'rateClient' => create(ExchangeRateClient::class),
    'cacheRateService' => create(FileCacheService::class)->constructor('cacheRate'),
    ExchangeRateProvider::class => create()->constructor(
        DI\get('rateClient'),
        DI\get('cacheRateService')
    ),
    BaseFetchRateService::class => create()->constructor(Di\get(ExchangeRateProvider::class)),

    //BinList Provider implementation
    //If resulted in a `429 Too Many Requests` response% try to use binProvider => LocalBinTestProvider::class
    'binClient' => create(BinListClient::class),
    'cacheBinService' => create(FileCacheService::class)->constructor('binList'),
    'binProvider' => create(BinListProvider::class)->constructor(
        DI\get('binClient'),
        DI\get('cacheBinService')
    ),
    //'binProvider' => create(LocalBinTestProvider::class), //For tests
    BaseFetchBinCountryService::class => create()->constructor(Di\get('binProvider')),

    //Main calculation service
    TransactionsCollection::class => create(),
    TransactionCollectionFactory::class => create()->constructor(DI\get(TransactionsCollection::class)),
    CalculateTransactionsService::class => create()->constructor(
        DI\get(BaseFetchBinCountryService::class),
        DI\get(BaseFetchRateService::class),
        DI\get(TransactionCollectionFactory::class)
    )
];