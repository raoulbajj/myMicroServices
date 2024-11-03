<?php

declare(strict_types=1);

use Slim\App;
use App\Controllers\AuthController;
use App\Middlewares\JwtMiddleware;
use App\Controllers\UserController;
use App\Controllers\MessageController;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {

    // REDIRECT FOR NON-EXISTING ROUTES
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        return $response;
    });

    // DEFAULT ROUTE AT "/"
    $app->get('/', function (Request $request, Response $response) {
        $response->getBody()->write('Hello world!');
        return $response;
    });



    // ==================================================================================
    // =============================== USERS CRUD ROUTES : ==============================
    $app->group('/user', function (Group $group) {
        // ROUTE POUR ADMIN SEULEMENT, NON MISE A DISPOSITION POUR LE CLIENT, donc pas de JWT
        $group->get('', UserController::class . ':getAllUsersInfo');

        $group->post('', UserController::class . ':createUser');
        $group->get('/email', UserController::class . ':getUserInfoByEmail');
        $group->get('/{id}', UserController::class . ':getUserInfo')->add(JwtMiddleware::class);
        $group->delete('/{id}', UserController::class . ':deleteUser')->add(JwtMiddleware::class);
    });

    $app->group('/user/update', function (Group $group) {
        $group->put('/Name/{id}', UserController::class . ':updateUserName')->add(JwtMiddleware::class);
        $group->put('/Password/{id}', UserController::class . ':updateUserPassword')->add(JwtMiddleware::class);
        $group->put('/Email/{id}', UserController::class . ':updateUserEmail')->add(JwtMiddleware::class);
        $group->patch('/{id}', UserController::class . ':updateUser')->add(JwtMiddleware::class);
    });


    // ============================= MESSAGES CRUD ROUTES : =============================
    $app->get('/msg', MessageController::class . ':getAllMessages');

    $app->group('/msg', function (Group $group) {
        $group->get('/{id}', MessageController::class . ':getMessage');
        $group->post('/{id}', MessageController::class . ':addMessage')->add(JwtMiddleware::class);
        $group->put('/{id}', MessageController::class . ':updateMessage')->add(JwtMiddleware::class);
        $group->delete('/{id}', MessageController::class . ':deleteMessage')->add(JwtMiddleware::class);
    });

    // ==================== LOGIN AUTH JWT ROUTE ========================
    $app->post('/login', AuthController::class . ':login');
    $app->post('/verify', AuthController::class . ':verifyToken');
};
