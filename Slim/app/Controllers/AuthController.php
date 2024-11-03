<?php

namespace App\Controllers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Models\UserModel;
use Psr\Http\Message\ResponseInterface as Response;

class AuthController
{
    public function login($request, Response $response): Response
    {
        $data = $request->getParsedBody();
        $userEmail = $data['email'] ?? '';
        $userPassword = md5($data['password']) ?? '';

        $user = UserModel::where('email', $userEmail)->first();
        if ($user && $user['password'] !== $userPassword) {
            $response->getBody()->write(json_encode(['error' => 'Wrong password, try again']));
            return $response->withStatus(401);
        }

        if (!$user) {
            $response->getBody()->write(json_encode(['error' => 'User not found']));
            return $response->withStatus(401);
        }

        // Génère le JWT
        $issuedAt = time();
        $expirationTime = $issuedAt + 3600;
        $payload = [
            'userid' => $user->id,
            'iat' => $issuedAt,
            'exp' => $expirationTime
        ];

        $token = JWT::encode($payload, 'elementalDhero', 'HS256');

        // Renvoie le Token et le userid + le nom de l'utilisateur
        $response->getBody()->write(json_encode(['token' => $token, 'userid' => $user->id, 'name' => $user->name]));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function verifyToken($request, Response $response): Response
    {
        $token = $request->getParsedBody()['token'] ?? null;
        if (!$token) {
            $response->getBody()->write(json_encode(['error' => 'No token provided']));
            return $response->withStatus(401);
        }

        try {
            $decoded = JWT::decode($token, new Key('elementalDhero', 'HS256'));
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode(['error' => 'Invalid token']));
            return $response->withStatus(401);
        }

        $response->getBody()->write(json_encode(['success' => 'Token is valid']));
        return $response->withHeader('Content-Type', 'application/json');
    }
}
