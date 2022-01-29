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
    public function __invoke(Request $request, GraphQLClient $graphQLClient, LoggerInterface $logger): JsonResponse
    {
        /** @psalm-suppress PossiblyInvalidArgument */
        $payload = decode($request->getContent());
        if (!isset($payload['activityData']['uniqueId'])) {
            return new JsonResponse(['error' => 'provide unique id', 'payload' => $payload]);
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
        if (!isset($payload['activityData']['lng'], $payload['activityData']['lat'])) {
            return new JsonResponse(['error' => 'not found lng or lat']);
        }
        $json = [];
        if ($payload['activityData']['region'] ?? false) {
            $json['region'] = $payload['activityData']['region'];
        }
        if ($payload['activityData']['area'] ?? false) {
            $json['area'] = $payload['activityData']['area'];
        }
        try {
            $graphQLClient->requestAuth(
                $query,
                [
                    'uid'   => $payload['activityData']['uniqueId'],
                    'point' => [
                        'type'        => 'Point',
                        'coordinates' => [$payload['activityData']['lng'], $payload['activityData']['lat']],
                    ],
                    'json'  => $json,
                ]
            );
        } catch (Throwable $e) {
            $logger->error($e->__toString());
            return new JsonResponse(['result' => 'get-user-error', 'message' => 'Activity update error']);
        }

        return new JsonResponse(['result' => 'get-user-success']);
    }
}
