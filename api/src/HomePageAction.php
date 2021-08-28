<?php

namespace App;

use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class HomePageAction extends AbstractController
{
    public function __invoke(Request $request, Connection $connection, GraphQLClient $graphQLClient) : JsonResponse
    {
        $community = $request->get('community');
        $query     = /** @lang GraphQL */
            <<<'GraphQL'
query($community: String!)  {
    activities: community_activity(
        where: {
            _and: [
            {communities: {community_id: {_eq: $community}}}
        ]
        },
        order_by: [{created_at: desc}]
    ) {
        id
        attachments
        description
        extra
        category
        geometry
        created_at
    }
    organization(
        where: {
            _and: [
                {communities: {community_id: {_eq: $community}}}, 
                {coordinates: {_is_null: false}}
            ]
        }
    ){
        id
        name
        geometry: coordinates
    }
}

GraphQL;
        $data      = $graphQLClient->request(
            $query,
            ['community' => $community ?: 'belarus']
        );

        return new JsonResponse([
            'type'     => 'FeatureCollection',
            'features' => (function (array $data) {
                $features = [];
                foreach ($data['activities'] as $activity) {
                    $features[] = [
                        'type'       => 'Feature',
                        'id'         => 'activity' . $activity['id'],
                        'properties' => [
                            'id'         => $activity['id'],
                            'name'       => $activity['extra']['name'] ?? $activity['description'],
                            'type'       => $activity['category'],
                            'created_at' => $activity['created_at'],
                        ],
                        'geometry' => $activity['geometry']
                    ];
                }
                foreach ($data['organization'] as $organization) {
                    $features[] = [
                        'type'       => 'Feature',
                        'id'         => 'activity' . $organization['id'],
                        'properties' => [
                            'id'         => $organization['id'],
                            'name'       => $organization['name'],
                            'type'       => 'organization'
                        ],
                        'geometry' => $organization['geometry']
                    ];
                }

                return $features;
            })($data),
        ]);
    }
}
