<?php

namespace App\Rating;

use App\Auth\ActionRequiresAuthorization;
use App\GraphQLClient;
use App\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use function Psl\Json\decode;

class DownvotePerson extends AbstractController implements ActionRequiresAuthorization
{
    public function __invoke(Request $request, GraphQLClient $graphQLClient, User $user): JsonResponse
    {
        /** @psalm-suppress all */
        $payload = decode($request->getContent());
        $response = $graphQLClient->requestAuth(/** @lang GraphQL */ <<<'GQL'
mutation($id: Int!, $user_id: Int!, $created: timestamp) {
    insert: insert_rating_point_one(
        object: {person_id: $id, type: "downvote", user_id: $user_id, created_at: $created},
        on_conflict: {constraint: rating_per_person_c, update_columns: [user_id, type]}
    ) {
        person {
            rating {
                upvotes
                downvotes
                rating: overall
            }
        }
    }
}
GQL,
            [
                'user_id' => $user->id(),
                'id' => $payload['input']['person_id'],
                'created' => date(DATE_ATOM)
            ]
        );

        return new JsonResponse($response['insert']['person']['rating']);
    }
}
