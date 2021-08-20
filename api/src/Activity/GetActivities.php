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
    public function __invoke(Request $request, GraphQLClient $graphQLClient) : JsonResponse
    {
        $date  = [
                'month' => '-1 month',
                'day'   => '-1 day',
                'week'  => '-1 week',
                'day3'  => '-3 days',
            ][$request->get('time')] ?? '-1 month';
        $query = /** @lang GraphQL */
            <<<'GraphQL'
query($timestamp: timestamp!)  {
    community_activity(
        where: {
            _and: [{status: {_eq: "APPROVED"}}, {validated_at: {_gte: $timestamp}}]
        },
        order_by: [{validated_at: desc}]
    ) {
        id
        attachments
        description
        extra
        category
    }
}
GraphQL;
        $data  = $graphQLClient->requestAuth(
            $query,
            ['timestamp' => (new DateTime($date))->setTime(0, 0)->format(DATE_ATOM)]
        );

        return new JsonResponse([
            'result'     => map(
                $data['community_activity'],
                fn (array $item) : array => [
                    '_id'         => $item['id'],
                    'direction'   => lowercase($item['category']),
                    'type'        => $item['attachments'][0]['type'] ?? 'text',
                    'url'         => $item['attachments'][0]['url'] ?? '',
                    'thumb'       => $item['attachments'][0]['thumb'] ?? '',
                    'lat'         => $item['geometry']['coordinates'][1] ?? null,
                    'lng'         => $item['geometry']['coordinates'][0] ?? null,
                    'description' => $item['description'],
                    'area'        => $item['extra']['area'] ?? '',
                    'region'      => $item['extra']['region'] ?? '',
                ]
            ),
            'statusText' => 'success',
        ]);
    }
}
