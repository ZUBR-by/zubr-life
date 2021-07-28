<?php

namespace App\Event;

use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class GetEventAction extends AbstractController
{
    public function __invoke(int $id, Request $request, Connection $connection) : JsonResponse
    {
        $data = $connection->fetchOne(<<<SQL
   SELECT JSON_OBJECT('data', JSON_OBJECT(
      'id', e.id,
      'name', e.name,
      'longitude', e.longitude,
      'latitude', e.latitude,
      'description', e.description,
      'attachments', JSON_QUERY(JSON_ARRAYAGG(e.attachments), '$[0]'),
      'created_at', DATE_FORMAT(e.created_at, '%d.%m.%Y')
    )
     )
     FROM event e
    WHERE e.id = ? AND e.hidden IS NULL AND approved IS NOT NULL
SQL
            ,
            [$id]
        );
        return JsonResponse::fromJsonString($data ?: '{"data":{}}}');
    }
}
