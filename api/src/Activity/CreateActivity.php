<?php

namespace App\Activity;

use App\Auth\BotAuthentication;
use App\Errors\GraphQLRequestError;
use App\GraphQLClient;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;
use function Psl\Json\decode;
use function Psl\Json\encode;
use function Psl\Str\replace;

class CreateActivity extends AbstractController implements BotAuthentication
{
    public function __invoke(Request $request, GraphQLClient $graphQLClient, LoggerInterface $logger): Response
    {
        /** @psalm-suppress PossiblyInvalidArgument */
        $payload = decode($request->getContent());
        if (!isset($payload['botId'], $payload['activityData']['direction'])) {
            return new JsonResponse(['error' => 'missing bot_id or direction']);
        }
        $payload              = array_merge($payload['activityData'], $payload);
        $query                =
            replace(
            /** @lang GraphQL */ <<<'GraphQL'
mutation (
    $point: geometry, 
    $communities: community_activity_relations_arr_rel_insert_input, 
    $extra: jsonb, 
    $date: timestamp, 
    $description: String,
    $user: telegram_user_obj_rel_insert_input,
    $attachments: jsonb,
    $uniqueId: String
) {
    insert_community_activity(
        objects: {
            category: NEWS,
            extra: $extra,
            created_at: $date,
            geometry: $point,
            description: $description,
            telegram_user: $user,
            communities: $communities,
            attachments: $attachments,
            unique_id: $uniqueId
        }
    ) {
        returning {
            id
            telegram_user {
                token
            }
        }
    }
}
GraphQL, 'NEWS', strtoupper($payload['direction']));
        $payload['community'] ??= 'belarus';
        $variables            = [
            'point'       => ($payload['lng'] ?? false) ? [
                'type'        => 'Point',
                'coordinates' => [$payload['lng'], $payload['lat']],
            ] : null,
            'communities' => ['data' => [['community_id' => $payload['community']]]],
            'extra'       => [
                'region'   => $payload['region'] ?? '',
                'area'     => $payload['area'] ?? '',
                'locked'   => false,
                'entities' => $payload['entities'] ?? []
            ],
            'date'        => date(DATE_ATOM),
            'user'        => [
                'data'        => ['user_id' => $payload['botId']],
                'on_conflict' => [
                    'constraint'     => 'telegram_user_id_pk',
                    'update_columns' => [],
                ],
            ],
            'uniqueId'    => $payload['unique_id'] ?? null,
            'description' => $payload['description'] ?? '',
            'attachments' => $payload['attachments'] ?? [],
        ];
        try {
            $data = $graphQLClient->requestAuth($query, $variables)['insert_community_activity']['returning'][0];
            syslog(LOG_INFO, encode($data));
            if (!empty($data['telegram_user']['token'])) {
                $query = /** @lang GraphQL */
                    <<<'GraphQL'
mutation($id: Int!, $json: jsonb, $date: timestamp) {
    update_community_activity_by_pk(_set: {status: APPROVED, validated_at: $date}, _append: {extra: $json}, pk_columns: {id: $id})  {
        id
        user_id
    }
}
GraphQL;
                $graphQLClient->requestAuth(
                    $query,
                    [
                        'id'   => $data['id'],
                        'json' => ['locked' => false, 'declineReason' => ''],
                        'date' => date(DATE_ATOM),
                    ]
                );
            }
            return new JsonResponse([
                'status' => 'get-user-success',
                'data'   => $data,
            ]);
        } catch (GraphQLRequestError $error) {
            $error->log($logger);
            if ($error->isFirstMessageEqualTo(
                'Uniqueness violation. duplicate key value violates unique constraint "community_activity_unique_id"')) {
                return new JsonResponse([
                    'status' => 'get-user-success',
                    'info'   => 'already exists with same unique id',
                ]);
            }
            return new JsonResponse(['status' => 'get-user-error', 'message' => 'Activity creation error']);
        } catch (Throwable $e) {
            $logger->error($e->__toString(), $payload);
            return new JsonResponse([
                'status' => 'get-user-error',
            ]);
        }
    }
}
