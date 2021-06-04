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
            'org', JSON_OBJECT('id', o.id, 'name', o.name)
           )
       ))
 FROM person as p
 JOIN persons_organizations po on p.id = po.person_id
 JOIN organization o on po.organization_id = o.id
SQL
        );
        return JsonResponse::fromJsonString($data ?: '{"data":[]}');
    }
}
