<?php

namespace App\Controllers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class DiscussionController
{
    public function getAllDiscussionsOfAUser(Request $request, Response $response, $args)
    {
        $client = new Client();

        try {
            $res = $client->request('GET', 'http://localhost:5001/discussion/getAllDiscussionsOfAUser/' . $args['id']);
            $discussionData = json_decode($res->getBody(), true);
            $response->getBody()->write(json_encode($discussionData, JSON_UNESCAPED_UNICODE));
            return $response;
        } catch (GuzzleException) {
            $response->getBody()->write(json_encode("Access denied or user not found : ", JSON_UNESCAPED_UNICODE));
            return $response->withStatus(401)->withHeader('Content-Type', 'application/json; charset=utf-8');
        }
    }

    public function getAllMsgOfADiscussion(Request $request, Response $response)
    {
        $client = new Client();

        $data = $request->getParsedBody();
        $discussionTitle = $data['title'];

        try {
            $res = $client->request('POST', 'http://localhost:5001/discussion/getAllMsgOfADiscussion', [
                'json' => ['title' => $discussionTitle]
            ]);

            $discussionMessages = json_decode($res->getBody(), true);
            $response->getBody()->write(json_encode($discussionMessages, JSON_UNESCAPED_UNICODE));
            return $response;
        } catch (GuzzleException $e) {
            $response->getBody()->write(json_encode("Error: " . $e->getMessage(), JSON_UNESCAPED_UNICODE));
            return $response->withStatus(500)->withHeader('Content-Type', 'application/json; charset=utf-8');
        }
    }

    public function createMsgWithinDiscussion(Request $request, Response $response)
    {
        $client = new Client();

        $token = $request->getHeaderLine('Authorization');
        $data = $request->getParsedBody();

        try {
            $res = $client->request('POST', 'http://localhost:8080/msg/' . $data['authorId'], [
                'headers' => [
                    'Authorization' => $token,
                ],
                'json' => [
                    "content" => $data['content'],
                    "discussion_id" => $data['discussion_id'],
                    "authorName" => $data['authorName']
                ]
            ]);

            // error_log($token);

            $discussionMessages = json_decode($res->getBody(), true);
            $response->getBody()->write(json_encode($discussionMessages, JSON_UNESCAPED_UNICODE));
            return $response;
        } catch (GuzzleException $e) {
            $response->getBody()->write(json_encode("Error: " . $e->getMessage(), JSON_UNESCAPED_UNICODE));
            return $response->withStatus(500)->withHeader('Content-Type', 'application/json; charset=utf-8');
        }
    }
}
