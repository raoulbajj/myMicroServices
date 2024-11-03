<?php

declare(strict_types=1);

use App\Application\Settings\Settings;
use App\Application\Settings\SettingsInterface;
use DI\ContainerBuilder;
use Monolog\Logger;
use Illuminate\Database\Capsule\Manager as Capsule;


return function (ContainerBuilder $containerBuilder) {
    // Global Settings Object
    $containerBuilder->addDefinitions([
        SettingsInterface::class => function () {
            return new Settings([
                'displayErrorDetails' => true, // Should be set to false in production
                'logError'            => false,
                'logErrorDetails'     => false,
                'logger' => [
                    'name' => 'slim-app',
                    'path' => isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../logs/app.log',
                    'level' => Logger::DEBUG,
                ]
            ]);
        }
    ]);



    // ========================================================
    // Setup de Eloquent ORM et configuration de la base de données
    $capsule = new Capsule;
    $capsule->addConnection([
        'driver'    => 'mysql',
        'host'      => '127.0.0.1',
        'port'      => '3306',
        'database'  => 'MMS',
        'username'  => 'root',
        'password'  => 'root',
        'charset'   => 'utf8mb4',
        'collation' => 'utf8mb4_general_ci',
        'prefix'    => ''
    ]);

    $capsule->setAsGlobal();
    $capsule->bootEloquent();
    // ========================================================
};
