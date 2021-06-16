<?php

namespace App\Place;

use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class GetPlaceAction extends AbstractController
{
    public function __invoke(int $id, Request $request, Connection $connection) : JsonResponse
    {
        $data = $connection->fetchOne(<<<SQL
   SELECT JSON_OBJECT('data', JSON_OBJECT(
      'id', p.id,
      'name', p.name,
      'longitude', p.longitude,
      'latitude', p.latitude,
      'description', p.description,
      'attachments', JSON_QUERY(JSON_ARRAYAGG(p.attachments), '$[0]')
    )
     )
     FROM place p
    WHERE p.id = ?
SQL
            ,
            [$id]
        );
        return JsonResponse::fromJsonString($data ?: '{"data":{}}}');
    }
}
