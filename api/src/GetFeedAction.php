<?php

namespace App;

use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class GetFeedAction extends AbstractController
{
    public function __invoke(Request $request, Connection $connection) : JsonResponse
    {
        $limit = $request->query->get('limit') ? 'LIMIT ' . $request->query->get('limit') : '';
        $data  = $connection->fetchOne(<<<SQL
SELECT JSON_OBJECT('data', JSON_ARRAYAGG(
           JSON_OBJECT(
            'id', id,
            'name', name,
            'type', type,
            'created_at', DATE_FORMAT(created_at, '%d.%m.%Y')
           )
           ORDER BY created_at DESC
           $limit
       ))
 FROM (
     SELECT id, 'ad' as type, name, created_at FROM ad WHERE hidden_at IS NULL
     UNION ALL
     SELECT id, 'event' as type, name, created_at FROM event WHERE hidden_at IS NULL
 ) as f
SQL
        );
        return JsonResponse::fromJsonString($data ?: '{"data":[]}');
    }
}
