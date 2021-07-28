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
                'rating', rating,
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
   SELECT o.id, name, longitude, latitude, null as created_at, 
          SUM(IF(rp.type = 'upvote', 1, IF(rp.type IS NULL, 0, -1))) as rating,
          'organization' as type 
     FROM organization o
LEFT JOIN rating_point rp on o.id = rp.organization_id 
    WHERE longitude is NOT NULL
 GROUP BY o.id
 UNION ALL
SELECT id, name, longitude, latitude, DATE_FORMAT(created_at, '%d.%m.%Y') as created_at,
       0 as rating,
       'event' as type
  FROM event
 WHERE longitude is NOT NULL AND hidden IS NULL AND approved IS NOT NULL
 UNION ALL
SELECT id, name, longitude, latitude, null as created_at,
       0 as rating,
       'ad' as type
  FROM ad
 WHERE longitude is NOT NULL AND hidden IS NULL AND approved IS NOT NULL
 UNION ALL
SELECT id, name, longitude, latitude, null as created_at, 
       0 as rating,
       'place' as type
  FROM place
 WHERE longitude is NOT NULL
) as f
SQL
        );
        return JsonResponse::fromJsonString($data ?: '{"data":{}}}');
    }
}
