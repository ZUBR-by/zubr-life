<?php

namespace App\Community;

use App\GraphQLClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class GetCommunities extends AbstractController
{
    public function __invoke(Request $request, GraphQLClient $graphQLClient)
    {
        $query = /** @lang GraphQL */
            <<<'GraphQL'
query {
    community(where: {id: {_neq: "belarus"}}) {
        id
        name
        geometry
    }
}
GraphQL;
        $data = $graphQLClient->request($query);

        return new JsonResponse([
            'result' => $data['community'],
            'statusText' => 'success',
        ]);
    }
}
