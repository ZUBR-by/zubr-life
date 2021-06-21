<?php

namespace App\Organization;

use App\Entity\User;
use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use function Psl\Json\decode;

class GetOrganizationAction extends AbstractController
{
    public function __invoke(int $id, Request $request, Connection $connection, User $user) : JsonResponse
    {
        $data = $connection->fetchOne(<<<SQL
   SELECT JSON_OBJECT(
      'id', o.id,
      'name', o.name,
      'longitude', o.longitude,
      'latitude', o.latitude,
      'address', o.address,
      'rating', JSON_OBJECT(
        'is_upvoted', is_upvoted,
        'is_downvoted', is_downoted,
        'upvotes', upvotes,
        'downvotes', downvotes
      ),
      'attachments', JSON_QUERY(JSON_ARRAYAGG(o.attachments), '$[0]'),
      'people_count', CAST(COUNT(DISTINCT p.id) as integer),
      'people', JSON_ARRAYAGG(DISTINCT JSON_OBJECT(
        'id', p.id,
        'full_name', p.full_name,
        'photo_url', p.photo_url,
        'description', p.description))
        )
     FROM organization o
LEFT JOIN persons_organizations po on o.id = po.organization_id
LEFT JOIN person as p on po.person_id = p.id
LEFT JOIN (
        SELECT rp.type = 'upvote' AND rp.user_id = :user as is_upvoted,
               rp.type = 'downvote' AND rp.user_id = :user as is_downoted,
               CAST(COUNT(IF(rp.type = 'upvote', 1, NULL)) as integer) as upvotes,
               CAST(COUNT(IF(rp.type = 'downvote', 1, NULL)) as integer) as downvotes,
               rp.organization_id
          FROM rating_point rp 
         WHERE rp.organization_id = :org
      GROUP BY rp.organization_id
        ) as rp on o.id = rp.organization_id
    WHERE o.id = :org
SQL
            ,
            ['user' => $user->id(), 'org' => $id]
        );
        if (empty($data)) {
            return JsonResponse::fromJsonString('{"data":{}}');
        }
        $data           = decode($data);
        $data['rating'] = decode($data['rating']);
        return new JsonResponse(['data' => $data]);
    }
}
