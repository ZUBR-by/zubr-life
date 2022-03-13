<?php

namespace App\Activity;

use App\Auth\BotAuthentication;
use App\Errors\GraphQLRequestError;
use App\GraphQLClient;
use lucadevelop\TelegramEntitiesDecoder\EntityDecoder;
use Psr\Log\LoggerInterface;
use stdClass;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;
use function Psl\Dict\map;
use function Psl\Json\decode;
use function Psl\Json\encode;
use function Psl\Str\replace;
use function Psl\Str\slice;

class AddActivity extends AbstractController implements BotAuthentication
{
    public function __invoke(Request $request, GraphQLClient $graphQLClient, LoggerInterface $logger): Response
    {
        $payload = decode((string)$request->getContent());
        if (!isset($payload['input'])) {
            return new JsonResponse(['error' => 'missing `input` field']);
        }
        $payload = $payload['input'];
        /** @psalm-suppress PossiblyInvalidArgument */
        if (!isset($payload['botId'], $payload['activityData']['direction'])) {
            return new JsonResponse(['error' => 'missing bot_id or direction']);
        }
        $payload = array_merge($payload['activityData'], $payload);
        $data    = $graphQLClient->requestAuth(/** @lang GraphQL */ <<<'GraphQL'
query ($user: Int!) {
    telegram_user_by_pk(user_id: $user) {
        user_id
    }
}
GraphQL,
            ['user' => $payload['botId']]
        );
        if (!$data['telegram_user_by_pk']) {
            $graphQLClient->requestAuth(/** @lang GraphQL */ <<<'GraphQL'
mutation ($user: Int!) {
    insert_telegram_user_one(
        object: {user_id: $user}
    ) {
        __typename
    }
}
GraphQL
                ,
                ['user' => $payload['botId']]
            );
        }
        $query                =
            replace(
            /** @lang GraphQL */ <<<'GraphQL'
mutation (
    $point: geometry, 
    $communities: community_activity_relations_arr_rel_insert_input, 
    $extra: jsonb, 
    $date: timestamp, 
    $description: String,
    $title: String,
    $user: Int,
    $attachments: [community_activity_attachment_insert_input!]!,
    $uniqueId: String
) {
    insert_community_activity(
        objects: {
            category: NEWS,
            extra: $extra,
            title: $title,
            created_at: $date,
            geometry: $point,
            description: $description,
            user_id: $user,
            communities: $communities,
            unique_id: $uniqueId,
            files: {data: $attachments}
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

        $entities      = $payload['entities'] ?? [];
        $description   = $payload['description'] ?? '';
        $message       = new stdClass();
        $message->text = $payload['description'] ?? '';
        if (count($entities) > 0) {
            $message->entities = (object)(map($entities, fn($item) => (object)$item));
            $description       = (new EntityDecoder())->decode($message);
        }
        $title       = (function (string $raw) {
            foreach ([PHP_EOL, '.', '!', '?'] as $break) {
                if (!str_contains($raw, $break)) {
                    continue;
                }
                return \Psl\Str\split($raw, $break)[0];
            }
            return slice($raw, 0, 50);
        })($message->text);
        $description = replace($description, "\n", '<br>');
        $variables   = [
            'point'       => ($payload['lng'] ?? false) ? [
                'type'        => 'Point',
                'coordinates' => [$payload['lng'], $payload['lat']],
            ] : null,
            'communities' => ['data' => [['community_id' => $payload['community']]]],
            'extra'       => [
                'region'               => $payload['region'] ?? '',
                'area'                 => $payload['area'] ?? '',
                'locked'               => false,
                'entities'             => $entities,
                'original_description' => $message->text,
                'content_type'         => 'html'
            ],
            'title'       => $title,
            'date'        => ($payload['created_at'] ?? null)
                ? date(DATE_ATOM, (int)$payload['created_at'])
                : date(DATE_ATOM),
            'user'        => $payload['botId'],
            'uniqueId'    => $payload['unique_id'] ?? null,
            'description' => $description,
            'attachments' => \Psl\Vec\map($payload['attachments'] ?? [], fn(array $item) => [
                'attachment_id' => $item['id'],
                'extra'         => [
                    'thumb' => $item['thumb'] ?? ''
                ]
            ]),
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
