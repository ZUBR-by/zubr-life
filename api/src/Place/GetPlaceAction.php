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
      'comments_count', cast(COUNT(DISTINCT c.id) as integer),
      'comments', JSON_ARRAYAGG(
          DISTINCT JSON_OBJECT(
              'text', text, 
              'created_at', c.created_at, 
              'attachments', c.attachments,
              'params', c.params
          )
      )
        )
     )
     FROM place p
LEFT JOIN comment c on p.id = c.event_id AND c.hidden_at IS NULL
    WHERE p.id = ?
SQL
            ,
            [$id]
        );
        return JsonResponse::fromJsonString($data ?: '{"data":{}}}');
    }
}
