<?php

namespace App;

use App\Services\CalculateTransactionsService;
use DI\ContainerBuilder;
use DI\DependencyException;
use DI\NotFoundException;
use Exception;

class App
{
    /**
     * @throws DependencyException
     * @throws NotFoundException
     * @throws Exception
     */
    public function run(string $data): void
    {
        $containerBuilder = new ContainerBuilder();
        $containerBuilder->addDefinitions(__DIR__  . '/Config/DIConfig.php');
        $container = $containerBuilder->build();

        $calculatingService = $container->get(CalculateTransactionsService::class);
        $testData = json_decode($data);

        $calculatingService->execute($testData);

        echo $calculatingService;
    }
}