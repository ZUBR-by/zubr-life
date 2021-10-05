<?php

namespace App\User;

use App\Auth\BotAuthentication;
use App\GraphQLClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use function Psl\Json\decode;

class RegisterUser extends AbstractController implements BotAuthentication
{
    public function __invoke(Request $request, GraphQLClient $graphQLClient) : JsonResponse
    {
        /** @psalm-suppress PossiblyInvalidArgument */
        $payload = decode($request->getContent());
        if (! isset($payload['botId'])) {
            return new JsonResponse(['error' => 'missing botId']);
        }

        $query = /** @lang GraphQL */
            <<<'GraphQL'
mutation($id: Int!, $token: String) {
    insert_telegram_user_one(object: {user_id: $id, token: $token}) {
        user_id
    }
}
GraphQL;

        $graphQLClient->requestAuth($query, ['id' => $payload['botId'], 'token' => $payload['token'] ?? '']);

        return new JsonResponse(['result' => 'success']);
    }
}
