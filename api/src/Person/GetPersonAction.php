<?php

namespace App\Person;

use App\Entity\User;
use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use function Psl\Json\decode;

class GetPersonAction extends AbstractController
{
    public function __invoke(int $id, Connection $connection, ?User $user) : JsonResponse
    {
        $data = $connection->fetchOne(<<<SQL
SELECT JSON_OBJECT(
    'id', p.id,
    'full_name', full_name,
    'description', description,
    'photo_url', photo_url,
    'rating', JSON_OBJECT(
        'is_upvoted', is_upvoted,
        'is_downvoted', is_downoted,
        'upvotes', upvotes,
        'downvotes', downvotes
     ),
    'attachments', JSON_QUERY(JSON_ARRAYAGG(p.attachments), '$[0]'),
    'organizations_count', cast(COUNT(DISTINCT o.id) as integer),
    'organizations', JSON_ARRAYAGG(DISTINCT JSON_OBJECT('id', o.id, 'name', o.name))
      )
FROM person as p
     LEFT JOIN persons_organizations po on p.id = po.person_id
     LEFT JOIN organization o on po.organization_id = o.id
     LEFT JOIN (
            SELECT rp.type = 'upvote' AND rp.user_id = :user as is_upvoted,
                   rp.type = 'downvote' AND rp.user_id = :user as is_downoted,
                   CAST(COUNT(IF(rp.type = 'upvote', 1, NULL)) as integer) as upvotes,
                   CAST(COUNT(IF(rp.type = 'downvote', 1, NULL)) as integer) as downvotes,
                   rp.person_id
              FROM rating_point rp 
             WHERE rp.person_id = :person
          GROUP BY rp.person_id
            ) as rp on p.id = rp.person_id
    WHERE p.id = :person
GROUP BY p.id
SQL
            ,
            ['user' => $user ? $user->id() : 0, 'person' => $id]
        );
        if (empty($data)) {
            return JsonResponse::fromJsonString('{"data":{}}');
        }
        $data           = decode($data);
        $data['rating'] = decode($data['rating']);
        return new JsonResponse(['data' => $data]);
    }
}
