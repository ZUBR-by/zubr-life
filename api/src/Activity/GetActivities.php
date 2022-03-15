<?php

namespace App\Activity;

use App\GraphQLClient;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use function Psl\Dict\map;
use function Psl\Str\lowercase;

class GetActivities extends AbstractController
{
    public function __invoke(Request $request, GraphQLClient $graphQLClient): JsonResponse
    {
        $date  = [
                     'month'  => '-1 month',
                     'month3' => '-3 month',
                     'month6' => '-6 month',
                     'day'    => '-1 day',
                     'week'   => '-1 week',
                     'day3'   => '-3 days',
                 ][$request->get('time')] ?? '-1 month';
        $query = /** @lang GraphQL */
            <<<'GraphQL'
query($timestamp: timestamp!)  {
    community_activity(
        where: {
            _and: [
            {status: {_eq: "APPROVED"}}, 
            {geometry: {_is_null: false}},
            {validated_at: {_gte: $timestamp}},
            {category: {_in: ["PROTEST","EVENT","ART"]}},
            {communities: {community_id: {_eq: "belarus"}}}
        ]
        },
        order_by: [{validated_at: desc}]
    ) {
        id
        attachments
        description
        extra
        category
        geometry
        title
        communities {
            community_id
        }
        files {
            extra
            attachment {
                url
                content_type
                extra
            }
        }
    }
}
GraphQL;
        $data  = $graphQLClient->requestAuth(
            $query,
            ['timestamp' => (new DateTime($date))->setTime(0, 0)->format(DATE_ATOM)]
        );

        $formatAttachmentType = fn(string $raw): string => str_contains($raw, '/') ? \Psl\Str\split($raw, '/')[0] : $raw;
        return new JsonResponse([
            'result'     => map(
                $data['community_activity'],
                fn(array $item): array => [
                    'id'          => $item['id'],
                    'direction'   => lowercase($item['category']),
                    'type'        => $formatAttachmentType($item['files'][0]['attachment']['content_type'] ?? 'text'),
                    'attachments' => map(
                        $item['files'],
                        fn(array $raw): array => [
                            'url'   => $raw['attachment']['url'],
                            'type'  => $formatAttachmentType($raw['attachment']['content_type']),
                            'thumb' => $raw['extra']['thumb'] ?? $raw['attachment']['thumb'] ?? ''
                        ]
                    ),
                    'geometry'    => $item['geometry'],
                    'description' => $item['description'],
                    'title'       => $item['title'],
                    'area'        => $item['extra']['area'] ?? '',
                    'region'      => $item['extra']['region'] ?? '',
                    'community'   => $item['communities']['community_id'] ?? 'belarus',
                ]
            ),
            'statusText' => 'success',
        ]);
    }
}
