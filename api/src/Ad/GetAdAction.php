<?php

namespace App\Ad;

use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class GetAdAction extends AbstractController
{
    public function __invoke(int $id, Request $request, Connection $connection) : JsonResponse
    {
        $data = $connection->fetchOne(<<<SQL
   SELECT JSON_OBJECT('data', JSON_OBJECT(
      'id', a.id,
      'name', a.name,
      'description', a.description,
      'longitude', a.longitude,
      'latitude', a.latitude,
      'attachments',JSON_QUERY(JSON_ARRAYAGG(a.attachments), '$[0]'),
      'created_at', DATE_FORMAT(a.created_at, '%d.%m.%Y')
        )
     )
     FROM ad a
LEFT JOIN comment c on a.id = c.ad_id AND c.hidden_at IS NULL
    WHERE a.id = ? AND a.hidden IS NULL AND approved IS NOT NULL
SQL
            ,
            [$id]
        );
        return JsonResponse::fromJsonString($data ?: '{"data":{}}}');
    }
}
