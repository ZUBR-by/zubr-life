<?php

namespace App\Ad;

use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class GetListAction extends AbstractController
{
    public function __invoke(Connection $connection): JsonResponse
    {
        return $this->json([
            'data' => $connection->fetchAllAssociative(
                'SELECT 
                        id, 
                        name, 
                        description 
                   FROM ad
               ORDER BY created_at DESC'
            ),
        ]);
    }
}
