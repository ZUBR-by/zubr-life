<?php

namespace App\Comments;

use App\GraphQLClient;
use App\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use function Psl\Json\decode;
use function Psl\Vec\map;

class GetListAction extends AbstractController
{
    public function __invoke(Request $request, GraphQLClient $graphQLClient, User $user): JsonResponse
    {
        /** @psalm-suppress PossiblyInvalidArgument */
        $content = decode($request->getContent());
        if (!isset($content['input']['id'], $content['input']['entity_type'])) {
            return new JsonResponse([]);
        }
        $response = $graphQLClient->requestAuth(/** @lang GraphQL */ <<<'GraphQL'
query($where: comment_bool_exp) {
    comment(where: $where) {
        id
        attachments
        text
        created_at
        user_id
    }
}
GraphQL,
            [
                'where' => [
                    $content['input']['entity_type'] . '_id' => ['_eq' => $content['input']['id']]
                ]
            ]
        );
        return new JsonResponse(
            map(
                $response['comment'],
                fn(array $item): array => [
                    'id' => $item['id'],
                    'attachments' => $item['attachments'],
                    'text' => $item['text'],
                    'created_at' => $item['created_at'],
                    'by_user' => $user->isEqual($item['user_id'])
                ]
            )
        );
    }
}
