<?php

require __DIR__ . '/../vendor/autoload.php';

$builder = new DI\ContainerBuilder();
$builder->useAnnotations(false);
$builder->addDefinitions(__DIR__ . '/../config/config.php');
return $builder->build();
