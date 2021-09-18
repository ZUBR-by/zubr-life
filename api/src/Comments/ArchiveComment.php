<?php

namespace App\Comments;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class ArchiveComment extends AbstractController
{
    public function __invoke(int $id) : JsonResponse
    {
        return new JsonResponse([]);
    }
}
