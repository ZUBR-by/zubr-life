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
SELECT JSON_OBJECT(
  'data', JSON_OBJECT(
    'id', p.id,
    'full_name', full_name,
    'description', description,
    'photo_url', photo_url,
    'attachments', JSON_QUERY(JSON_ARRAYAGG(o.attachments), '$[0]'),
    'organizations_count', cast(COUNT(DISTINCT o.id) as integer),
    'organizations', JSON_ARRAYAGG(DISTINCT JSON_OBJECT('id', o.id, 'name', o.name)),
    'comments_count', cast(COUNT(DISTINCT c.id) as integer),
    'comments', JSON_ARRAYAGG(
      DISTINCT JSON_OBJECT(
        'text', text,
        'created_at', created_at,
        'attachments', c.attachments,
        'params', c.params
          )
        )
      )
    )
FROM person as p
     LEFT JOIN persons_organizations po on p.id = po.person_id
     LEFT JOIN organization o on po.organization_id = o.id
     LEFT JOIN comment c on p.id = c.person_id AND c.hidden_at IS NULL
WHERE p.id = ?
SQL
            ,
            [$id]
        );
        return JsonResponse::fromJsonString($data ?: '{"data":{}}');
    }
}
