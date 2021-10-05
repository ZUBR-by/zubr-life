<?php

namespace App\User;

use App\Auth\BotAuthentication;
use App\GraphQLClient;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Throwable;
use function Psl\Json\decode;

class UpdateUser extends AbstractController implements BotAuthentication
{
    public function __invoke(Request $request, GraphQLClient $graphQLClient, LoggerInterface $logger) : JsonResponse
    {
        /** @psalm-suppress PossiblyInvalidArgument */
        $payload = decode($request->getContent());
        if (! isset($payload['botId'])) {
            return new JsonResponse(['error' => 'missing botId']);
        }

        $query = /** @lang GraphQL */
            <<<'GraphQL'
mutation($id: Int!, $json: jsonb) {
    update_telegram_user_by_pk(pk_columns: {user_id: $id}, _append: {extra: $json}) {
        user_id
    }
}
GraphQL;
        try {
            $graphQLClient->requestAuth(
                $query,
                [
                    'json' => [
                        'lng'    => $payload['newData']['lng'] ?? null,
                        'lat'    => $payload['newData']['lat'] ?? null,
                        'area'   => $payload['newData']['area'] ?? '',
                        'region' => $payload['newData']['region'] ?? '',
                    ],
                    'id'   => $payload['botId'],
                ]
            );
        } catch (Throwable $e) {
            $logger->error($e->__toString());
            return new JsonResponse(['status' => 'get-user-error']);

        }


        return new JsonResponse(['result' => 'success', 'status' => 'get-user-success']);
    }
}
