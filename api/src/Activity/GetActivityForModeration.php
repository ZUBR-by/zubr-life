<?php

namespace App\Activity;

use App\Auth\BotAuthentication;
use App\GraphQLClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class GetActivityForModeration extends AbstractController implements BotAuthentication
{
    public function __invoke(Request $request, GraphQLClient $graphQLClient): JsonResponse
    {
        $query = /** @lang GraphQL */
            <<<'GraphQL'
query($contains: jsonb)  {
    community_activity(
        where: {
            _and: [
                {status: {_eq: "VALIDATION"}},
                {_or: [{extra: {_contains: $contains}}, {_not: {extra: {_has_key: "locked"}}}]}
            ],
        },
        order_by: [{created_at: asc}]
        limit: 1
    ) {
        id
        attachments
        extra
        description
    }
}
GraphQL;
        $data  = $graphQLClient->requestAuth(
                $query,
                ['contains' => ['locked' => false]]
            )['community_activity'][0] ?? null;
        if ($data !== null) {
            $data  = [
                'id'          => $data['id'],
                'url'         => $data['attachments'][0]['url'] ?? '',
                'type'        => $data['attachments'][0]['type'] ?? 'text',
                'area'        => $data['extra']['area'] ?? '',
                'region'      => $data['extra']['region'] ?? '',
                'description' => $data['description'],
            ];
            $query = /** @lang GraphQL */
                <<<'GraphQL'
mutation ($id: Int!, $json: jsonb)  {
    update_community_activity_by_pk(pk_columns: {id: $id}, _append: {extra: $json}) {
        id
    }
}
GraphQL;
            $graphQLClient->requestAuth($query, ['id' => $data['id'], 'json' => ['locked' => true]]);
        }

        return new JsonResponse(
            [
                'result' => $data,
                'status' => $data !== null ? 'success' : 'empty',
            ]
        );
    }
}
