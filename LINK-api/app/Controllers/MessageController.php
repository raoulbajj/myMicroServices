<?php

namespace App\Controllers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class MessageController
{
    public function getAllMessages(Request $request, Response $response, $args)
    {
        $client = new Client();
        $res = $client->request('GET', 'http://localhost:8080/msg');
        $messageData = json_decode($res->getBody(), true);
        $response->getBody()->write(json_encode($messageData, JSON_UNESCAPED_UNICODE));
        return $response;
    }

    public function getMessage(Request $request, Response $response, $args)
    {
        $client = new Client();
        $res = $client->request('GET', 'http://localhost:8080/msg/' . $args['id']);
        $messageData = json_decode($res->getBody(), true);
        $response->getBody()->write(json_encode($messageData, JSON_UNESCAPED_UNICODE));
        return $response;
    }

    public function addMessage(Request $request, Response $response, $args)
    {
        $client = new Client();
        $token = $request->getHeaderLine('Authorization');

        try {
            $res = $client->request('POST', 'http://localhost:8080/msg/' . $args['id'], [
                'headers' => [
                    'Authorization' => $token,
                ],
                'form_params' => [
                    'content' => $request->getParsedBody()['content'],
                    'discussion_id' => $request->getParsedBody()['discussion_id'],
                ]
            ]);
            $messageData = json_decode($res->getBody(), true);
            $response->getBody()->write(json_encode($messageData, JSON_UNESCAPED_UNICODE));
            return $response;
        } catch (GuzzleException $e) {
            $response->getBody()->write(json_encode("Access denied or user not found", JSON_UNESCAPED_UNICODE));
            return $response->withStatus(401)->withHeader('Content-Type', 'application/json; charset=utf-8');
        }
    }

    public function updateMessage(Request $request, Response $response, $args)
    {
        $client = new Client();
        $token = $request->getHeaderLine('Authorization');

        try {
            $res = $client->request('PUT', 'http://localhost:8080/msg/' . $args['id'], [
                'headers' => [
                    'Authorization' => $token,
                ],
                'form_params' => [
                    'content' => $request->getParsedBody()['content'],
                ]
            ]);
            $messageData = json_decode($res->getBody(), true);
            $response->getBody()->write(json_encode($messageData, JSON_UNESCAPED_UNICODE));
            return $response;
        } catch (GuzzleException $e) {
            $response->getBody()->write(json_encode("Access denied or user not found", JSON_UNESCAPED_UNICODE));
            return $response->withStatus(401)->withHeader('Content-Type', 'application/json; charset=utf-8');
        }
    }

    public function deleteMessage(Request $request, Response $response, $args)
    {
        $client = new Client();
        $token = $request->getHeaderLine('Authorization');

        try {
            $res = $client->request('DELETE', 'http://localhost:8080/msg/' . $args['id'], [
                'headers' => [
                    'Authorization' => $token,
                ],
            ]);
            $messageData = json_decode($res->getBody(), true);
            $response->getBody()->write(json_encode($messageData, JSON_UNESCAPED_UNICODE));
            return $response;
        } catch (GuzzleException $e) {
            $response->getBody()->write(json_encode("Access denied or user not found", JSON_UNESCAPED_UNICODE));
            return $response->withStatus(401)->withHeader('Content-Type', 'application/json; charset=utf-8');
        }
    }
}
