<?php

namespace App\Organization;

use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class GetOrganizationsAction extends AbstractController
{
    public function __invoke(Request $request, Connection $connection) : JsonResponse
    {
        $data = $connection->fetchOne(<<<SQL
SELECT JSON_OBJECT('data', JSON_ARRAYAGG(
         JSON_MERGE(
           JSON_OBJECT(
            'id', organization.id,
            'name', name,
            'longitude', longitude,
            'latitude', latitude
           ),
          params
         )
       ))
 FROM organization
SQL
        );
        return JsonResponse::fromJsonString($data ?: '{"data":[]}');
    }
}
