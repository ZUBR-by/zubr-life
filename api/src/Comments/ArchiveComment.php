<?php

namespace App\Comments;

use App\GraphQLClient;
use App\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class ArchiveComment extends AbstractController
{
    public function __invoke(int $id, GraphQLClient $graphQLClient, User $user): JsonResponse
    {
        return new JsonResponse($graphQLClient->requestUser(/** @lang GraphQL */ <<<'GraphQL'
mutation($id: Int!, $date: timestamp) {
    update_comment_by_pk(pk_columns: {id: $id}, _set: {hidden_at: $date}) {
        id
    }
}
GraphQL
            ,
            [
                'id'   => $id,
                'date' => date(DATE_ATOM)
            ],
            $user
        ));
    }
}
