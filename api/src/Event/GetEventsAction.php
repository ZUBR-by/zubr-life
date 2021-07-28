<?php

namespace App\Event;

use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class GetEventsAction extends AbstractController
{
    public function __invoke(Request $request, Connection $connection) : JsonResponse
    {
        $data = $connection->fetchOne(<<<SQL
SELECT JSON_OBJECT('data', JSON_ARRAYAGG(
         JSON_MERGE(
           JSON_OBJECT(
            'id', id,
            'name', name,
            'longitude', longitude,
            'latitude', latitude,
            'created_at', DATE_FORMAT(created_at, '%d.%m.%Y')
           ),
          params
         )
        ORDER BY created_at DESC
       ))
  FROM event 
 WHERE hidden IS NULL AND approved IS NOT NULL
SQL
        );
        return JsonResponse::fromJsonString($data ?: '{"data":[]}');
    }
}
