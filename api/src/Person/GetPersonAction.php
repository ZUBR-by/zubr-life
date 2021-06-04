<?php

namespace App\Person;

use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class GetPersonAction extends AbstractController
{
    public function __invoke(int $id, Connection $connection) : JsonResponse
    {
        $data = $connection->fetchOne(<<<SQL
    SELECT JSON_OBJECT('data', JSON_OBJECT(
                'id', p.id,
                'full_name', full_name,
                'description', description,
                'photo_url', photo_url,
                'org', JSON_OBJECT('id', o.id, 'name', o.name)
               )
           )
      FROM person as p
 LEFT JOIN persons_organizations po on p.id = po.person_id
 LEFT JOIN organization o on po.organization_id = o.id
     WHERE p.id = ?
SQL
            ,
            [$id]
        );
        return JsonResponse::fromJsonString($data ?: '{"data":{}}');
    }
}
