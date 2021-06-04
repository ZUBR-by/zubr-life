<?php

namespace App;

use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class HomePageAction extends AbstractController
{
    public function __invoke(Connection $connection)
    {
        $data = $connection->fetchOne(<<<SQL
SELECT JSON_OBJECT(
    'type', 'FeatureCollection',
    'features', JSON_ARRAYAGG(
         JSON_OBJECT(
             'type', 'Feature',
            'id', organization.id,
            'properties', JSON_OBJECT('name', name),
             'geometry', JSON_OBJECT(
                 'type', 'Point',
                 'coordinates', JSON_ARRAY(longitude, latitude)
             )
           )
       ))
 FROM organization
SQL
        );
        return JsonResponse::fromJsonString($data ?: '{"data":{}}}');
    }
}
