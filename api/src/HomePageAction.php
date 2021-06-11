<?php

namespace App;

use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class HomePageAction extends AbstractController
{
    public function __invoke(Connection $connection) : JsonResponse
    {
        $data = $connection->fetchOne(<<<SQL
SELECT JSON_OBJECT(
        'type', 'FeatureCollection',
        'features', JSON_ARRAYAGG(
         JSON_OBJECT(
             'type', 'Feature',
            'id', CONCAT(type,id),
            'properties', JSON_OBJECT(
                'id', id, 
                'name', name, 
                'type', type,
                'created_at', created_at
            ),
            'geometry', JSON_OBJECT(
                 'type', 'Point',
                 'coordinates', JSON_ARRAY(longitude, latitude)
             )
           )
       )
      )
 FROM (
SELECT id, name, longitude, latitude, null as created_at, 
       'organization' as type 
  FROM organization
 WHERE longitude is NOT NULL
 UNION ALL
SELECT id, name, longitude, latitude, DATE_FORMAT(created_at, '%d.%m.%Y') as created_at, 
       'event' as type 
  FROM event
 WHERE longitude is NOT NULL AND hidden_at IS NULL
 UNION ALL
SELECT id, name, longitude, latitude, null as created_at, 
       'ad' as type 
  FROM ad
 WHERE longitude is NOT NULL AND hidden_at IS NULL
 UNION ALL
SELECT id, name, longitude, latitude, null as created_at, 
       'place' as type 
  FROM place
 WHERE longitude is NOT NULL
) as f
SQL
        );
        return JsonResponse::fromJsonString($data ?: '{"data":{}}}');
    }
}
