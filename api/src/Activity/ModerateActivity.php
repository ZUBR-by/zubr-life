<?php

namespace App\Activity;

use App\Auth\BotAuthentication;
use App\GraphQLClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use function Psl\Json\decode;
use function Psl\Str\replace;

class ModerateActivity extends AbstractController implements BotAuthentication
{
    public function __invoke(Request $request, GraphQLClient $graphQLClient): JsonResponse
    {
        /** @psalm-suppress PossiblyInvalidArgument */
        $payload = decode($request->getContent());
        if (!isset($payload['id'], $payload['moderationStatus'])) {
            return new JsonResponse(['error' => 'missing id or status', 'payload' => $payload]);
        }

        $query = replace(/** @lang GraphQL */ <<<'GraphQL'
mutation($id: Int!, $json: jsonb, $date: timestamp) {
    update_community_activity_by_pk(_set: {status: STATUS, validated_at: $date}, _append: {extra: $json}, pk_columns: {id: $id})  {
        id
        user_id
    }
}
GraphQL, 'STATUS', strtoupper($payload['moderationStatus'])
        );

        $data = $graphQLClient->requestAuth(
            $query,
            [
                'id'   => $payload['id'],
                'json' => ['locked' => false, 'declineReason' => $payload['declineReason'] ?? ''],
                'date' => date(DATE_ATOM),
            ]
        );
        if ($data['update_community_activity_by_pk'] !== null && $payload['moderationStatus'] === 'BLOCKED') {
            $query = /** @lang GraphQL */
                <<<'GraphQL'
mutation($uid: Int!, $json: jsonb) {
    update_telegram_user_by_pk(pk_columns: {user_id: $uid}, _set: {blocked: $json}) {
        user_id
    }
}
GraphQL;
            $graphQLClient->requestAuth(
                $query,
                [
                    'uid'  => $data['update_community_activity_by_pk']['user_id'],
                    'json' => ['blocked_at' => date(DATE_ATOM)],
                ]
            );
        }

        return new JsonResponse(['result' => $data['update_community_activity_by_pk'], 'status' => 'success']);
    }
}
