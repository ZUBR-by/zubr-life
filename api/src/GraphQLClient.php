<?php

namespace App;

use App\Auth\JWTFactory;
use App\Errors\GraphQLRequestError;
use GuzzleHttp\Client;
use function Psl\Json\decode;

class GraphQLClient
{
    private string $graphqlUrl;
    private JWTFactory $JWTFactory;

    public function __construct(string $graphqlUrl, JWTFactory $JWTFactory)
    {
        $this->graphqlUrl = $graphqlUrl;
        $this->JWTFactory = $JWTFactory;
    }

    public function request(string $query, array $variables = []): array
    {
        $request = ['query' => $query];
        if (!empty($variables)) {
            $request['variables'] = $variables;
        }
        $response = (new Client())->post(
            $this->graphqlUrl,
            ['json' => $request]
        );
        $raw      = decode($response->getBody()->getContents());
        if (isset($raw['errors'])) {
            throw new GraphQLRequestError($raw['errors'], $variables);
        }
        return $raw['data'];
    }

    public function requestAuth(string $query, array $variables = []): array
    {
        $jwt      = $this->JWTFactory->encode(
            [
                'hasura' => [
                    'x-hasura-allowed-roles' => ['community_moderator'],
                    'x-hasura-default-role'  => 'community_moderator',
                ],
                'exp'    => time() + 38 * 24 * 60 * 60,
            ]
        );
        $response = (new Client())->post(
            $this->graphqlUrl,
            [
                'headers' => [
                    'Cookie' => 'AUTH=' . $jwt,
                ],
                'json'    => [
                    'query'     => $query,
                    'variables' => $variables,
                ],
            ]
        );
        $raw      = decode($response->getBody()->getContents());
        if (isset($raw['errors'])) {
            throw new GraphQLRequestError($raw['errors'], $variables);
        }
        return $raw['data'];
    }
}
