<?php

namespace App\Ad;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class GetEventAction extends AbstractController
{
    public function __invoke(int $id) : JsonResponse
    {
        return new JsonResponse(['id' => $id]);
    }
}
