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

    public function requestUser(string $query, array $variables = [], User $user = null): array
    {
        $head = [
            'x-hasura-allowed-roles' => ['life_user'],
            'x-hasura-default-role'  => 'life_user',
        ];
        if ($user) {
            $head['x-hasura-user-id'] = (string)$user->id();
        }
        $jwt  = $this->JWTFactory->encode(
            [
                'hasura' => $head,
                'exp'    => time() + 38 * 24 * 60 * 60,
            ]
        );
        $body = ['query' => $query];
        if (!empty($variables)) {
            $body['variables'] = $variables;
        }
        $response = (new Client())->post(
            $this->graphqlUrl,
            [
                'headers' => [
                    'Cookie' => 'AUTH=' . $jwt,
                ],
                'json'    => $body,
            ]
        );
        $raw      = decode($response->getBody()->getContents());
        if (isset($raw['errors'])) {
            throw new GraphQLRequestError($raw['errors'], $variables);
        }
        return $raw['data'];
    }

    public function requestAuth(string $query, array $variables = []): array
    {
        $jwt  = $this->JWTFactory->encode(
            [
                'hasura' => [
                    'x-hasura-allowed-roles' => ['community_moderator'],
                    'x-hasura-default-role'  => 'community_moderator',
                ],
                'exp'    => time() + 38 * 24 * 60 * 60,
            ]
        );
        $body = [
            'query' => $query,
        ];
        if (!empty($variables)) {
            $body['variables'] = $variables;
        }
        $response = (new Client())->post(
            $this->graphqlUrl,
            [
                'headers' => [
                    'Cookie' => 'AUTH=' . $jwt,
                ],
                'json'    => $body,
            ]
        );
        $raw      = decode($response->getBody()->getContents());
        if (isset($raw['errors'])) {
            throw new GraphQLRequestError($raw['errors'], $variables);
        }
        return $raw['data'];
    }

    public function requestObserverBot(string $query, array $variables = []): array
    {
        $jwt  = $this->JWTFactory->encode(
            [
                'hasura' => [
                    'x-hasura-allowed-roles' => ['observer_bot'],
                    'x-hasura-default-role'  => 'observer_bot',
                ],
                'exp'    => time() + 38 * 24 * 60 * 60,
            ]
        );
        $body = [
            'query' => $query,
        ];
        if (!empty($variables)) {
            $body['variables'] = $variables;
        }
        $response = (new Client())->post(
            $this->graphqlUrl,
            [
                'headers' => [
                    'Cookie' => 'AUTH=' . $jwt,
                ],
                'json'    => $body,
            ]
        );
        $raw      = decode($response->getBody()->getContents());
        if (isset($raw['errors'])) {
            throw new GraphQLRequestError($raw['errors'], $variables);
        }
        return $raw['data'];
    }
}
