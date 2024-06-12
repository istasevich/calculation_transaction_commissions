<?php

use App\App;

require_once 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/');
$dotenv->load();

$source = file_get_contents("input.json");

$app = new App();

try {
    $app->run($source);
} catch (Exception $exception) {
    echo "Error!\n";
    echo $exception->getMessage();
}
