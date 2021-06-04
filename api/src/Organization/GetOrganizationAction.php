<?php

namespace App\Organization;

use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class GetOrganizationAction extends AbstractController
{
    public function __invoke(int $id, Request $request, Connection $connection) : JsonResponse
    {
        $data = $connection->fetchOne(<<<SQL
   SELECT JSON_OBJECT('data', JSON_OBJECT(
      'id', o.id,
      'name', o.name,
      'comments_count', cast(COUNT(DISTINCT c.id) as integer),
      'comments', JSON_ARRAYAGG(
          DISTINCT JSON_OBJECT(
              'text', text, 
              'created_at', created_at, 
              'attachments', c.attachments,
              'params', c.params
          )
      ),
      'people', JSON_ARRAYAGG(DISTINCT JSON_OBJECT(
        'id', p.id,
        'name', p.full_name,
        'description', p.description
          ))
        )
     )
     FROM organization o
LEFT JOIN persons_organizations po on o.id = po.organization_id
LEFT JOIN person as p on po.person_id = p.id
LEFT JOIN comment c on o.id = c.organization_id AND c.hidden_at IS NULL
    WHERE o.id = ?
SQL
            ,
            [$id]
        );
        return JsonResponse::fromJsonString($data ?: '{"data":{}}}');
    }
}
