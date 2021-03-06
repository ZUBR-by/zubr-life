<?php

namespace App\Comments;

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
        User $user
    ) : JsonResponse {
        if (! in_array($type, ['ad', 'place', 'event', 'organization', 'person'])) {
            return new JsonResponse(['data' => []]);
        }
        $data = $dbal->fetchOne(<<<SQL
   SELECT JSON_OBJECT('data', JSON_ARRAYAGG(
            JSON_OBJECT(
              'id',id,
              'text', text,
              'created_at', DATE_ADD(created_at, INTERVAL 3 HOUR), 
              'created_at_formatted', DATE_FORMAT(DATE_ADD(created_at, INTERVAL 3 HOUR), '%d.%m.%Y %H:%i'), 
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
        if ($data === '{"data": null}') {
            return JsonResponse::fromJsonString('{"data":[]}');
        }
        return JsonResponse::fromJsonString($data ?: '{"data":[]}');
    }
}
