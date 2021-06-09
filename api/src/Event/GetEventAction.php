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
      'created_at', DATE_FORMAT(e.created_at, '%d.%m.%Y'),
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
     FROM event e
LEFT JOIN comment c on e.id = c.event_id AND c.hidden_at IS NULL
    WHERE e.id = ?
SQL
            ,
            [$id]
        );
        return JsonResponse::fromJsonString($data ?: '{"data":{}}}');
    }
}
