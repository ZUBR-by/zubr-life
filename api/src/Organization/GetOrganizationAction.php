<?php

namespace App\Organization;

use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class GetOrganizationAction extends AbstractController
{
    public function __invoke(int     $id, Request $request, Connection $connection) : JsonResponse
    {
        $data = $connection->fetchOne(<<<SQL
   SELECT JSON_OBJECT('data', JSON_OBJECT(
      'id', o.id,
      'name', o.name,
      'longitude', o.longitude,
      'latitude', o.latitude,
      'address', o.address,
      'attachments', JSON_QUERY(JSON_ARRAYAGG(o.attachments), '$[0]'),
      'people_count', CAST(COUNT(DISTINCT p.id) as integer),
      'people', JSON_ARRAYAGG(DISTINCT JSON_OBJECT(
        'id', p.id,
        'full_name', p.full_name,
        'photo_url', p.photo_url,
        'description', p.description
          ))
        )
     )
     FROM organization o
LEFT JOIN persons_organizations po on o.id = po.organization_id
LEFT JOIN person as p on po.person_id = p.id
    WHERE o.id = ?
SQL
            ,
            [$id]
        );
        return JsonResponse::fromJsonString($data ?: '{"data":{}}}');
    }
}
