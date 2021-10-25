<?php

namespace App;

use App\Errors\GraphQLRequestError;
use Firebase\JWT\JWT;
use GuzzleHttp\Client;
use function Psl\Filesystem\read_file;
use function Psl\Json\decode;

class GraphQLClient
{
    private string $graphqlUrl;
    private string $graphPrivateKey;
    private string $graphJwtAlgo;

    public function __construct(string $graphqlUrl, string $graphPrivateKey, string $graphJwtAlgo)
    {
        $this->graphqlUrl = $graphqlUrl;
        $this->graphPrivateKey = $graphPrivateKey;
        $this->graphJwtAlgo = $graphJwtAlgo;
    }

    public function request(string $query, array $variables = []): array
    {
        $request = [
            'query' => $query
        ];
        if (!empty($variables)) {
            $request['variables'] = $variables;
        }
        $response = (new Client())->post(
            $this->graphqlUrl,
            [
                'json' => $request,
            ]
        );
        $raw = decode($response->getBody()->getContents());
        if (isset($raw['errors'])) {
            throw new GraphQLRequestError($raw['errors'], $variables);
        }
        return $raw['data'];
    }

    public function requestAuth(string $query, array $variables = []): array
    {
        $jwt = JWT::encode(
            [
                'hasura' => [
                    'x-hasura-allowed-roles' => ['community_moderator'],
                    'x-hasura-default-role' => 'community_moderator',
                ],
                'exp' => time() + 38 * 24 * 60 * 60,
            ],
            read_file($this->graphPrivateKey),
            $this->graphJwtAlgo
        );
        $response = (new Client())->post(
            $this->graphqlUrl,
            [
                'headers' => [
                    'Cookie' => 'AUTH=' . $jwt,
                ],
                'json' => [
                    'query' => $query,
                    'variables' => $variables,
                ],
            ]
        );

        $raw = decode($response->getBody()->getContents());
        if (isset($raw['errors'])) {
            throw new GraphQLRequestError($raw['errors'], $variables);
        }
        return $raw['data'];
    }
}
