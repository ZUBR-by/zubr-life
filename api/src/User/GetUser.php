<?php

namespace App\User;

use App\Auth\BotAuthentication;
use App\GraphQLClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use function Psl\Dict\merge;

class GetUser extends AbstractController implements BotAuthentication
{
    public function __invoke(Request $request, GraphQLClient $graphQLClient): JsonResponse
    {
        $botId = $request->get('botId');
        if (empty($botId)) {
            return new JsonResponse(['error' => 'missing bot id']);
        }
        $query = /** @lang GraphQL */
            <<<'GraphQL'
query($uid: Int!)  {
    telegram_user_by_pk(user_id: $uid) {
        user_id
        blocked
        token
        extra
    }
}
GraphQL;
        $data = $graphQLClient->requestAuth($query, ['uid' => $botId]);
        if ($data['telegram_user_by_pk'] === null) {
            return new JsonResponse(['result' => null, 'status' => 'get-user-error']);
        }

        return new JsonResponse(
            [
                'result' => merge(
                    [
                        '_id' => $data['telegram_user_by_pk']['user_id'],
                        'active' => $data['telegram_user_by_pk']['blocked'] === null,
                        'admin' => !empty($data['telegram_user_by_pk']['token']),
                    ],
                    $data['telegram_user_by_pk']['extra']
                ),
                'status' => 'get-user-success',
            ]
        );
    }
}
