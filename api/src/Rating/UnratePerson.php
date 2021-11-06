<?php

namespace App\Rating;

use App\Auth\ActionRequiresAuthorization;
use App\GraphQLClient;
use App\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use function Psl\Json\decode;

class UnratePerson extends AbstractController implements ActionRequiresAuthorization
{
    public function __invoke(Request $request, GraphQLClient $graphQLClient, User $user): JsonResponse
    {
        /** @psalm-suppress all */
        $payload  = decode($request->getContent());
        $response = $graphQLClient->requestUser(/** @lang GraphQL */ <<<'GQL'
mutation($id: Int!, $user_id: Int!) {
    unrate: delete_rating_point(where: {user_id: {_eq: $user_id}, person_id: {_eq: $id}}) {
        returning {
           person {
              rating {
                downvotes
                upvotes
                is_downvoted
                is_upvoted
                overall
              }
           }
        }
    }
}
GQL,
            [
                'user_id' => $user->id(),
                'id'      => $payload['input']['person_id']
            ],
            $user
        );

        return new JsonResponse(
            $response['unrate']['returning'][0]['person']['rating']
            ?? ['rating' => 0, 'upvotes' => 0, 'downvotes' => 0]
        );
    }
}
