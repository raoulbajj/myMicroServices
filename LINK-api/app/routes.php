<?php

declare(strict_types=1);

use Slim\App;
use App\Controllers\AuthController;
use App\Controllers\UserController;
use App\Controllers\MessageController;
use App\Controllers\DiscussionController;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        return $response;
    });

    $app->get('/', function (Request $request, Response $response) {
        $response->getBody()->write('Hello world ! This is the LINK API !');
        return $response;
    });

    // ============================= USERS CRUD ROUTES : =============================
    $app->group('/user', function (Group $group) {
        $group->get('', UserController::class . ':getAllUsersInfo');
        $group->get('/email', UserController::class . ':getUserInfoByEmail');
        $group->get('/{id}', UserController::class . ':getUserInfoById');

        $group->post('', UserController::class . ':createUser');

        $group->delete('/{id}', UserController::class . ':deleteUser');
    });

    $app->group('/user/update', function (Group $group) {
        // =============================================================================
        // Not needed, already have PATCH route, but working fine il the first SLIME API
        // $group->put('/Name/{id}', UserController::class . ':updateUserName');
        // $group->put('/Password/{id}', UserController::class . ':updateUserPassword');
        // $group->put('/Email/{id}', UserController::class . ':updateUserEmail');
        // =============================================================================
        $group->patch('/{id}', UserController::class . ':updateUser');
    });

    // ============================= MESSAGES CRUD ROUTES : =============================
    $app->get('/msg', MessageController::class . ':getAllMessages');

    $app->group('/msg', function (Group $group) {
        $group->get('/{id}', MessageController::class . ':getMessage');
        $group->post('/{id}', MessageController::class . ':addMessage');
        $group->put('/{id}', MessageController::class . ':updateMessage');
        $group->delete('/{id}', MessageController::class . ':deleteMessage');
    });

    // ============================= DISCUSSIONS CRUD ROUTES : =============================
    $app->group('/discussion', function (Group $group) {
        $group->get('/getAllDiscussionsOfAUser/{id}', DiscussionController::class . ':getAllDiscussionsOfAUser');
        $group->post('/getAllMsgOfADiscussion', DiscussionController::class . ':getAllMsgOfADiscussion');
        $group->post('/createMsgWithinDiscussion', DiscussionController::class . ':createMsgWithinDiscussion');
    });

    // ==================== LOGIN ROUTE ========================
    $app->post('/login', AuthController::class . ':login');
    $app->post('/verify', AuthController::class . ':verifyToken');
};
