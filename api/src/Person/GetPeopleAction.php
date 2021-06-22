<?php

namespace App\Person;

use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class GetPeopleAction extends AbstractController
{
    public function __invoke(Request $request, Connection $connection): JsonResponse
    {
        $data = $connection->fetchOne(<<<SQL
SELECT JSON_OBJECT('data', JSON_ARRAYAGG(
              JSON_OBJECT(
                'id', p.id,
                'full_name', full_name,
                'description', description,
                'photo_url', photo_url,
                'rating', rating,
                'org', JSON_OBJECT('id', org_id, 'name', org_name)
              )
              ORDER BY rating DESC, full_name
          )
       )
FROM ( SELECT p.id,
              full_name,
              p.description,
              photo_url,
              SUM(IF(rp.type = 'upvote', 1, IF(rp.type IS NULL, 0, -1))) as rating,
              o.id as org_id,
              o.name as org_name
         FROM person as p
    LEFT JOIN persons_organizations po on p.id = po.person_id
    LEFT JOIN organization o on po.organization_id = o.id
    LEFT JOIN rating_point rp on p.id = rp.person_id
     GROUP BY p.id
) as p
SQL
        );
        return JsonResponse::fromJsonString($data ?: '{"data":[]}');
    }
}
