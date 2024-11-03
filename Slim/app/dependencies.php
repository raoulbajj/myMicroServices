<?php

declare(strict_types=1);

use DI\ContainerBuilder;
use Psr\Container\ContainerInterface;
use Illuminate\Database\Capsule\Manager as Capsule;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Processor\UidProcessor;
use Psr\Log\LoggerInterface;
use App\Application\Settings\SettingsInterface;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        LoggerInterface::class => function (ContainerInterface $c) {
            $settings = $c->get(SettingsInterface::class);
            $loggerSettings = $settings->get('logger');
            $logger = new Logger($loggerSettings['name']);

            $processor = new UidProcessor();
            $logger->pushProcessor($processor);

            $handler = new StreamHandler($loggerSettings['path'], $loggerSettings['level']);
            $logger->pushHandler($handler);

            return $logger;
        },

        'db' => function (ContainerInterface $container) {
            $capsule = new Capsule;

            $settings = $container->get(SettingsInterface::class);
            $dbSettings = $settings->get('db');

            $capsule->addConnection($dbSettings);

            $capsule->setAsGlobal();
            $capsule->bootEloquent();

            return $capsule;
        },
    ]);
};