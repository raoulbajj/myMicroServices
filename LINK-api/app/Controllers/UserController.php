<?php

namespace App\Controllers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UserController
{
    public function getAllUsersInfo(Request $request, Response $response, $args)
    {
        $client = new Client();
        $res = $client->request('GET', 'http://localhost:8080/user');
        $messageData = json_decode($res->getBody(), true);
        $response->getBody()->write(json_encode($messageData, JSON_UNESCAPED_UNICODE));
        return $response;
    }

    public function getUserInfoByEmail(Request $request, Response $response, $args)
    {
        $client = new Client();
        $res = $client->request('GET', 'http://localhost:8080/user/email', [
            'query' => [
                'email' => $request->getQueryParams()['email']
            ]
        ]);
        $messageData = json_decode($res->getBody(), true);
        $response->getBody()->write(json_encode($messageData, JSON_UNESCAPED_UNICODE));
        return $response;
    }

    public function getUserInfoById(Request $request, Response $response, $args)
    {
        $client = new Client();
        $token = $request->getHeaderLine('Authorization');

        try {
            $res = $client->request('GET', 'http://localhost:8080/user/' . $args['id'], [
                'headers' => [
                    'Authorization' => $token
                ]
            ]);

            $messageData = json_decode($res->getBody(), true);
            $response->getBody()->write(json_encode($messageData, JSON_UNESCAPED_UNICODE));
        } catch (GuzzleException $e) {
            $response->getBody()->write(json_encode(['error' => $e->getMessage()], JSON_UNESCAPED_UNICODE));
            return $response->withStatus(500);
        }

        return $response->withHeader('Content-Type', 'application/json');
    }

    public function createUser(Request $request, Response $response)
    {
        $client = new Client();
        $userData = $request->getParsedBody();

        try {
            $res = $client->request('POST', 'http://localhost:8080/user', [
                'form_params' => [
                    'name' => $userData['name'],
                    'email' => $userData['email'],
                    'password' => $userData['password']
                ]
            ]);

            $messageData = json_decode($res->getBody(), true);
            $response->getBody()->write(json_encode($messageData, JSON_UNESCAPED_UNICODE));
        } catch (GuzzleException $e) {
            $response->getBody()->write(json_encode(['error' => $e->getMessage()], JSON_UNESCAPED_UNICODE));
            return $response->withStatus(500);
        }

        return $response->withHeader('Content-Type', 'application/json');
    }

    public function updateUser(Request $request, Response $response, $args)
    {
        $client = new Client();
        $token = $request->getHeaderLine('Authorization');
        $userData = $request->getParsedBody();

        try {
            $res = $client->request('PATCH', 'http://localhost:8080/user/update/' . $args['id'], [
                'headers' => [
                    'Authorization' => $token
                ],
                'form_params' => [
                    'name' => $userData['name'],
                    'email' => $userData['email'],
                    'password' => $userData['password']
                ]
            ]);

            $messageData = json_decode($res->getBody(), true);
            $response->getBody()->write(json_encode($messageData, JSON_UNESCAPED_UNICODE));
        } catch (GuzzleException $e) {
            $response->getBody()->write(json_encode(['error' => $e->getMessage()], JSON_UNESCAPED_UNICODE));
            return $response->withStatus(500);
        }

        return $response->withHeader('Content-Type', 'application/json');
    }

    public function deleteUser(Request $request, Response $response, $args)
    {
        $client = new Client();
        $token = $request->getHeaderLine('Authorization');

        try {
            $res = $client->request('DELETE', 'http://localhost:8080/user/' . $args['id'], [
                'headers' => [
                    'Authorization' => $token
                ]
            ]);

            $messageData = json_decode($res->getBody(), true);
            $response->getBody()->write(json_encode($messageData, JSON_UNESCAPED_UNICODE));
        } catch (GuzzleException $e) {
            $response->getBody()->write(json_encode(['error' => $e->getMessage()], JSON_UNESCAPED_UNICODE));
            return $response->withStatus(500);
        }

        return $response->withHeader('Content-Type', 'application/json');
    }
}
