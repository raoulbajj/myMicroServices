<?php

namespace App\Middlewares;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Slim\Psr7\Response;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class JwtMiddleware
{
    public function __invoke($request, RequestHandler $handler): Response
    {
        $headers = $request->getHeader('Authorization');
        if (empty($headers)) {
            $response = new Response();
            $response->getBody()->write(json_encode(['error' => 'Token is required']));
            return $response->withStatus(401);
        }

        $token = explode(' ', $headers[0])[1] ?? '';

        try {
            $decoded = JWT::decode($token, new Key('elementalDhero', 'HS256'));
            $userIdFromToken = $decoded->userid;
            $request = $request->withAttribute('userIdFromToken', $userIdFromToken);
        } catch (\Exception $e) {
            $response = new Response();
            $response->getBody()->write(json_encode(['error' => 'Access refused', 'message' => $e->getMessage()]));
            return $response->withStatus(401);
        }

        return $handler->handle($request);
    }
}
