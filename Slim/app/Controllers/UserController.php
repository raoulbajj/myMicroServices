<?php

namespace App\Controllers;

use App\Models\UserModel;
use Psr\Http\Message\ResponseInterface as Response;
use App\Controllers\MessageController;

class UserController
{
    public function getAllUsersInfo($request, $response, $args): Response
    {
        $userData = UserModel::all();

        header('Content-Type: application/json; charset=utf-8');
        $response->getBody()->write(json_encode($userData, JSON_UNESCAPED_UNICODE));
        return $response;
    }

    public function getUserInfo($request, Response $response, $id): Response
    {
        $myUser = UserModel::findOrFail($id);
        $userIdFromToken = $request->getAttribute('userIdFromToken');

        if ($userIdFromToken !== $myUser[0]->id) {
            header('Content-Type: application/json; charset=utf-8');
            $response->getBody()->write(json_encode("You can't access another user's data !", JSON_UNESCAPED_UNICODE));
            return $response;
        }

        header('Content-Type: application/json; charset=utf-8');
        $response->getBody()->write(json_encode($myUser, JSON_UNESCAPED_UNICODE));
        return $response;
    }

    public function getUserInfoByEmail($request, Response $response, $args): Response
    {
        $queryParams = $request->getQueryParams();
        $email = $queryParams['email'] ?? null;

        if (!$email) {
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }

        $userData = UserModel::where('email', $email)->first();

        header('Content-Type: application/json; charset=utf-8');
        $response->getBody()->write(json_encode(([$userData->email, $userData->name]), JSON_PRETTY_PRINT));
        return $response;
    }

    public function createUser($request, Response $response): Response
    {
        $userData = $request->getParsedBody();

        $user = new UserModel();
        $user->name = $userData['name'];
        $user->email = $userData['email'];
        $user->password = md5($userData['password']);
        $user->save();

        $response->getBody()->write(json_encode("New user successfully created !", JSON_UNESCAPED_UNICODE));
        header('Content-Type: application/json; charset=utf-8');
        return $response;
    }

    public function updateUserName($request, Response $response, $id): Response
    {
        $myUser = UserModel::findOrFail($id);
        $userIdFromToken = $request->getAttribute('userIdFromToken');

        if ($userIdFromToken !== $myUser[0]->id) {
            header('Content-Type: application/json; charset=utf-8');
            $response->getBody()->write(json_encode("You can't update another user's data !", JSON_UNESCAPED_UNICODE));
        }

        $userData = $request->getParsedBody();

        $myUser[0]->name = $userData['name'];
        $myUser[0]->save();

        header('Content-Type: application/json; charset=utf-8');
        $response->getBody()->write(json_encode("User's NAME successfully updated !", JSON_UNESCAPED_UNICODE));
        return $response;
    }

    public function updateUserPassword($request, Response $response, $id): Response
    {
        $myUser = UserModel::findOrFail($id);
        $userIdFromToken = $request->getAttribute('userIdFromToken');

        if ($userIdFromToken !== $myUser[0]->id) {
            header('Content-Type: application/json; charset=utf-8');
            $response->getBody()->write(json_encode("You can't update another user's data !", JSON_UNESCAPED_UNICODE));
        }

        $userData = $request->getParsedBody();

        $myUser[0]->password = md5($userData['password']);
        $myUser[0]->save();

        header('Content-Type: application/json; charset=utf-8');
        $response->getBody()->write(json_encode("User's PASSWORD successfully updated !", JSON_UNESCAPED_UNICODE));
        return $response;
    }

    public function updateUserEmail($request, Response $response, $id): Response
    {
        $myUser = UserModel::findOrFail($id);
        $userIdFromToken = $request->getAttribute('userIdFromToken');

        if ($userIdFromToken !== $myUser[0]->id) {
            header('Content-Type: application/json; charset=utf-8');
            $response->getBody()->write(json_encode("You can't update another user's data !", JSON_UNESCAPED_UNICODE));
        }

        $userData = $request->getParsedBody();

        $myUser[0]->email = $userData['email'];
        $myUser[0]->save();

        header('Content-Type: application/json; charset=utf-8');
        $response->getBody()->write(json_encode("User's EMAIL successfully updated !", JSON_UNESCAPED_UNICODE));
        return $response;
    }

    public function updateUser($request, Response $response, $id): Response
    {
        $myUser = UserModel::findOrFail($id);
        $userIdFromToken = $request->getAttribute('userIdFromToken');

        if ($userIdFromToken !== $myUser[0]->id) {
            header('Content-Type: application/json; charset=utf-8');
            $response->getBody()->write(json_encode("You can't update another user's data !", JSON_UNESCAPED_UNICODE));
        }

        $userData = $request->getParsedBody();

        foreach ($userData as $key => $value) {
            if ($key === 'password' && !empty($key[0])) {
                $myUser[0]->$key = md5($value);
            } else if (!empty($key[0]))
                $myUser[0]->$key = $value;
            $myUser[0]->save();
        }

        header('Content-Type: application/json; charset=utf-8');
        $response->getBody()->write(json_encode("User successfully updated !", JSON_UNESCAPED_UNICODE));
        return $response;
    }

    public function deleteUser($request, Response $response, $id): Response
    {
        $myUser = UserModel::findOrFail($id);
        $userIdFromToken = $request->getAttribute('userIdFromToken');

        if($userIdFromToken !== $myUser[0]->id) {
            header('Content-Type: application/json; charset=utf-8');
            $response->getBody()->write(json_encode("Something went wrong...", JSON_UNESCAPED_UNICODE));
            return $response;
        }

        $messageController = new MessageController();
        $messageController->deleteAllMessagesFromOneUser($request, $response, $id);

        $myUser[0]->delete();

        header('Content-Type: application/json; charset=utf-8');
        $response->getBody()->write(json_encode("User successfully DELETED !", JSON_UNESCAPED_UNICODE));
        return $response;
    }
}
