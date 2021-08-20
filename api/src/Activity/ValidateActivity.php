<?php

namespace App\Activity;

use App\Auth\BotAuthentication;
use App\GraphQLClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ValidateActivity extends AbstractController implements BotAuthentication
{
    public function __invoke(Request $request, GraphQLClient $graphQLClient) : JsonResponse
    {
        $id = $request->get('uniqueId');
        if (empty($id)) {
            return new JsonResponse(['error' => 'provide unique id']);
        }
        $query = /** @lang GraphQL */
            <<<'GraphQL'
query($uid: String!)  {
    community_activity(where: {unique_id: {_eq: $uid}}) {
        id
    }
}
GraphQL;
        $data  = $graphQLClient->requestAuth($query, ['uid' => $id]);

        return new JsonResponse(['result' => count($data['community_activity']) === 1]);
    }
}
