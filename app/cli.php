#!/usr/bin/env php
<?php

use BotNamespace\UpdateHandle;
use Psr\Container\ContainerInterface;
use TgBotApi\BotApiBase\BotApi;
use TgBotApi\BotApiBase\Method\GetUpdatesMethod;

if ('cli' !== PHP_SAPI) {
    return;
}

ini_set('display_errors', 1);
error_reporting(E_ALL);

/** @var ContainerInterface $container */
$container = require(__DIR__ . '/bootstrap.php');

$api = $container->get(BotApi::class);
$updateHandle = $container->get(UpdateHandle::class);

$lastUpdateId = 0;

//special for @mike_iceman
while (true) {
    $updates = $api->getUpdates(GetUpdatesMethod::create($lastUpdateId ? ['offset' => $lastUpdateId + 1] : []));
    foreach ($updates as $update) {
        $updateHandle->handle($update);
        $lastUpdateId = $update->updateId;
    }
    usleep(100000);
}
