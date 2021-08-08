<?php

namespace App\Event;

use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use function Psl\Json\decode;
use function Psl\Json\encode;

class GetEventAction extends AbstractController
{
    public function __invoke(int $id, Request $request, Connection $connection) : JsonResponse
    {
        $data = $connection->fetchAssociative(<<<SQL
   SELECT e.id,
        e.name,
        e.longitude,
        e.latitude,
        e.description,
        e.attachments,
        DATE_FORMAT(e.created_at, '%d.%m.%Y') as created_at
     FROM event e
    WHERE e.id = ? AND e.hidden IS NULL AND approved IS NOT NULL
SQL
            ,
            [$id]
        );
        if ($data === false) {
            return JsonResponse::fromJsonString('{"data":{}}');
        }
        $data['id'] = (int) $data['id'];
        if ($data['longitude']) {
            $data['longitude'] = (float) $data['longitude'];
        }
        if ($data['latitude']) {
            $data['latitude'] = (float) $data['latitude'];
        }

        $data['attachments'] = decode($data['attachments']);

        return JsonResponse::fromJsonString(encode(['data' => $data]));
    }
}
