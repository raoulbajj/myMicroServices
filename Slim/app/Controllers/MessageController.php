<?php

namespace App\Controllers;

use App\Models\MessageModel;
use App\Models\UserModel;
use Psr\Http\Message\ResponseInterface as Response;

class MessageController
{
    public function getAllMessages($request, $response, $args): Response
    {
        $messageData = MessageModel::all();

        header('Content-Type: application/json; charset=utf-8');
        $response->getBody()->write(json_encode($messageData, JSON_UNESCAPED_UNICODE));
        return $response;
    }

    public function getMessage($request, Response $response, $id): Response
    {
        $messageData = MessageModel::findOrFail($id);

        header('Content-Type: application/json; charset=utf-8');
        $response->getBody()->write(json_encode($messageData[0], JSON_UNESCAPED_UNICODE));
        return $response;
    }

    public function addMessage($request, Response $response, $id): Response
    {
        $userIdFromToken = $request->getAttribute('userIdFromToken');

        $myUser = UserModel::findOrFail($id)->first();

        if ($myUser && $userIdFromToken == $myUser->id) {

            $client = new \GuzzleHttp\Client();
            $res = $client->request('GET', 'http://localhost:5001/discussion/getDiscussionById/' . $request->getParsedBody()['discussion_id']);

            // error_log("GET Discussion Response: " . print_r($res->getBody()->getContents(), true));

            if ($res->getStatusCode() != 200) {
                $response->getBody()->write(json_encode("Discussion not found", JSON_UNESCAPED_UNICODE));
                return $response->withStatus(401)->withHeader('Content-Type', 'application/json; charset=utf-8');
            } else {
                $discussionData = json_decode($res->getBody(), true);
                if (!in_array($myUser->id, $discussionData['users'])) {
                    $response->getBody()->write(json_encode("Access denied", JSON_UNESCAPED_UNICODE));
                    return $response->withStatus(401)->withHeader('Content-Type', 'application/json; charset=utf-8');
                }
            }

            $messageData = $request->getParsedBody();

            $message = MessageModel::create([
                'content' => $messageData['content'],
                'user_id' => $myUser->id,
                'discussion_id' => $messageData['discussion_id'],
                'author' => $myUser->name
            ]);

            $client = new \GuzzleHttp\Client();
            $res = $client->request('GET', 'http://localhost:5001/discussion/getDiscussionById/' . $messageData['discussion_id']);
            $discussionData = json_decode($res->getBody(), true);


            $client = new \GuzzleHttp\Client();
            $res = $client->request('POST', 'http://localhost:5001/discussion/createMsgWithinDiscussion', [
                'json' => [
                    'title' => $discussionData['title'],
                    'message' => $messageData['content'],
                    'messages' => [$myUser->name],
                    'author' => $myUser->id
                ]
            ]);

            header('Content-Type: application/json; charset=utf-8');
            $response->getBody()->write(json_encode("Message successfully added !", JSON_UNESCAPED_UNICODE));
            return $response;
        } else {
            $response->getBody()->write(json_encode("Access denied or user not found", JSON_UNESCAPED_UNICODE));
            return $response->withStatus(401)->withHeader('Content-Type', 'application/json; charset=utf-8');
        }
    }


    public function updateMessage($request, Response $response, $idMsg): Response
    {
        $userIdFromToken = $request->getAttribute('userIdFromToken');
        $myUser = UserModel::findOrFail($userIdFromToken);

        if ($myUser->messages()->where('id', $idMsg)->exists() == false) {
            header('Content-Type: application/json; charset=utf-8');
            $response->getBody()->write(json_encode("Access denied or message not found", JSON_UNESCAPED_UNICODE));
            return $response->withStatus(401)->withHeader('Content-Type', 'application/json; charset=utf-8');
        }

        $myMessage = MessageModel::findOrFail($idMsg);

        $messageData = $request->getParsedBody();

        $myMessage[0]->content = $messageData['content'];
        $myMessage[0]->save();

        header('Content-Type: application/json; charset=utf-8');
        $response->getBody()->write(json_encode("Message successfully updated !", JSON_UNESCAPED_UNICODE));
        return $response;
    }

    public function deleteMessage($request, Response $response, $idMsg): Response
    {
        $userIdFromToken = $request->getAttribute('userIdFromToken');
        $myUser = UserModel::findOrFail($userIdFromToken);

        if ($myUser->messages()->where('id', $idMsg)->exists() == false) {
            header('Content-Type: application/json; charset=utf-8');
            $response->getBody()->write(json_encode("Access denied or message not found", JSON_UNESCAPED_UNICODE));
            return $response->withStatus(401)->withHeader('Content-Type', 'application/json; charset=utf-8');
        }

        $myMessage = MessageModel::findOrFail($idMsg);

        $myMessage[0]->delete();

        header('Content-Type: application/json; charset=utf-8');
        $response->getBody()->write(json_encode("Message successfully DELETED !", JSON_UNESCAPED_UNICODE));
        return $response;
    }

    public function deleteAllMessagesFromOneUser($request, Response $response, $id): Response
    {
        $myUser = UserModel::findOrFail($id);

        $myUser[0]->messages()->delete();

        header('Content-Type: application/json; charset=utf-8');
        return $response;
    }
}
