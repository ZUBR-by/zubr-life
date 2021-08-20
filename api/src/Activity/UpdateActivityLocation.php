<?php

namespace App\Activity;

use App\Auth\BotAuthentication;
use App\GraphQLClient;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Throwable;
use function Psl\Json\decode;

class UpdateActivityLocation extends AbstractController implements BotAuthentication
{
    public function __invoke(Request $request, GraphQLClient $graphQLClient, LoggerInterface $logger) : JsonResponse
    {
        /** @psalm-suppress PossiblyInvalidArgument */
        $payload = decode($request->getContent());
        if (! isset($payload['uniqueId'])) {
            return new JsonResponse(['error' => 'provide unique id']);
        }
        $query = /** @lang GraphQL */
            <<<'GraphQL'
mutation($point: geometry, $uid: String!, $json: jsonb) {
    update_community_activity(_append: {extra: $json}, _set: {geometry: $point}, where: {unique_id: {_eq: $uid}}) {
        affected_rows
        returning {
            id
        }
    }
}
GraphQL;
        if (! isset($payload['lng'], $payload['lat'])) {
            return new JsonResponse(['error' => 'not found lng or lat']);
        }
        try {
            $graphQLClient->request(
                $query,
                [
                    'uid'   => $payload['uniqueId'],
                    'point' => [
                        'type'        => 'Point',
                        'coordinates' => [$payload['lng'], $payload['lat']],
                    ],
                    'json'  => [
                        'region' => $payload['region'] ?? '',
                        'area'   => $payload['area'] ?? '',
                    ],
                ]
            );
        } catch (Throwable $e) {
            $logger->error($e->__toString());
            return new JsonResponse(['result' => 'get-user-error', 'message' => 'Activity update error']);
        }

        return new JsonResponse(['result' => 'get-user-success']);
    }
}
