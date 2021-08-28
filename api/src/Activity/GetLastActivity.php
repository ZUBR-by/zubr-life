<?php

namespace App\Activity;

use App\Auth\BotAuthentication;
use App\GraphQLClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class GetLastActivity extends AbstractController implements BotAuthentication
{
    public function __invoke(Request $request, GraphQLClient $graphQLClient) : JsonResponse
    {
        $botId = $request->get('botId');
        if (! is_numeric($botId)) {
            return new JsonResponse(['error' => 'invalid bot_id']);
        }
        $query = /** @lang GraphQL */
            <<<'GraphQL'
query($user: Int!)  {
    community_activity(
        where: {
            _and: [
                {user_id: {_eq: $user}}
            ],
        },
        order_by: [{created_at: desc}]
        limit: 1
    ) {
        id
        attachments
        extra
        description
        status
    }
}
GraphQL;
        $data  = $graphQLClient->requestAuth(
                $query,
                ['contains' => ['locked' => false]]
            )['community_activity'][0] ?? null;
        if ($data !== null) {
            $data = [
                'id'            => $data['id'],
                'attachments'   => $data['attachments'],
                'status'        => $data['status'],
                'declineReason' => $data['extra']['declineReason'] ?? '',
                'area'          => $data['extra']['area'] ?? '',
                'region'        => $data['extra']['region'] ?? '',
                'description'   => $data['extra']['description'] ?? '',
            ];
        }

        return new JsonResponse(
            [
                'result' => $data,
                'status' => $data !== null ? 'success' : 'empty',
            ]
        );
    }
}
