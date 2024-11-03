<?php

namespace App\Controllers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AuthController
{
    public function login(Request $request, Response $response)
    {
        $data = $request->getParsedBody();
        $email = $data['email'] ?? null;
        $password = $data['password'] ?? null;

        $url = 'http://localhost:8080/login';
        $client = new Client();

        try {
            $response = $client->request('POST', $url, [
                'json' => ['email' => $email, 'password' => $password]
            ]);

            $body = $response->getBody();
            $decodedResponse = json_decode($body, true);

            if (isset($decodedResponse['token'])) {
                return $response->withHeader('Content-Type', 'application/json');
            }
        } catch (GuzzleException $e) {
            $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
        return $response->withStatus(404);
    }

    public function verifyToken(Request $request, Response $response)
    {
        $token = $request->getParsedBody()['token'] ?? null;
        if (!$token) {
            $response->getBody()->write(json_encode(['error' => 'No token provided']));
            return $response->withStatus(401);
        }

        $url = 'http://localhost:8080/verify';
        $client = new Client();

        try {
            $response = $client->request('POST', $url, [
                'json' => ['token' => $token]
            ]);

            $body = $response->getBody();
            $decodedResponse = json_decode($body, true);

            if (isset($decodedResponse['success'])) {
                return $response->withHeader('Content-Type', 'application/json');
            }
        } catch (GuzzleException $e) {
            $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
        return $response->withStatus(404);
    }
}
