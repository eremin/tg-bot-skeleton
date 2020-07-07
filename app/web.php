<?php

use BotNamespace\Router;
use Psr\Container\ContainerInterface;
use Spiral\Debug;
use Spiral\Goridge\StreamRelay;
use Spiral\RoadRunner\PSR7Client;
use Spiral\RoadRunner\Worker;

/** @var ContainerInterface $container */
$container = require(__DIR__ . '/bootstrap.php');

$router = $container->get(Router::class);

// worker.php
ini_set('display_errors', 'stderr');

$relay = new StreamRelay(STDIN, STDOUT);
$psr7 = new PSR7Client(new Worker($relay));

$dumper = $container->get(Debug\Dumper::class);

while ($req = $psr7->acceptRequest()) {
    try {
        $psr7->respond($router->handle($req));
    } catch (\Throwable $e) {
        $dumper->dump($e, Debug\Dumper::ERROR_LOG);
        $psr7->getWorker()->error((string)$e);
    }
}
