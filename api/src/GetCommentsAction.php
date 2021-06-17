<?php

namespace App;

use App\Entity\User;
use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class GetCommentsAction extends AbstractController
{
    public function __invoke(
        string $id,
        string $type,
        Request $request,
        Connection $dbal,
        ?User $user = null
    ) : JsonResponse {
        if (! in_array($type, ['ad', 'place', 'event', 'organization', 'person'])) {
            return new JsonResponse(['data' => []]);
        }
        $user = $user ?: new User(0);
        $data = $dbal->fetchOne(<<<SQL
   SELECT JSON_OBJECT('data', JSON_ARRAYAGG(
            JSON_OBJECT(
              'text', text, 
              'created_at', created_at, 
              'created_at_formatted', DATE_FORMAT(created_at, '%d.%m.%Y %H:%i'), 
              'attachments', attachments,
              'params', params,
              'can_delete', user_id = ?
            )
            ORDER BY created_at desc
          ))
     FROM comment
    WHERE {$type}_id = ?
SQL
            ,
            [$user->id(), $id]
        );
        return JsonResponse::fromJsonString($data ?: '{"data":{}}}');
    }
}
