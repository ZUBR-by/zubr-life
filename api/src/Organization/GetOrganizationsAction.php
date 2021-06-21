<?php

namespace App\Organization;

use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class GetOrganizationsAction extends AbstractController
{
    public function __invoke(Request $request, Connection $connection) : JsonResponse
    {
        $data = $connection->fetchOne(<<<SQL
SELECT JSON_OBJECT('data', JSON_ARRAYAGG(
         JSON_OBJECT(
            'id', id,
            'name', name,
            'rating', rating 
           )
        ORDER BY rating DESC
       ))
FROM (
     SELECT o.id, 
            name,
            SUM(IF(rp.type = 'upvote', 1, IF(rp.type IS NULL, 0, -1))) as rating
       FROM organization o
  LEFT JOIN rating_point rp on o.id = rp.organization_id
   GROUP BY o.id 
) as o
 
SQL
        );
        return JsonResponse::fromJsonString($data ?: '{"data":[]}');
    }
}
