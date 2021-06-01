<?php

namespace App\Event;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class GetEventsAction extends AbstractController
{
    public function __invoke(Request $request): JsonResponse
    {
        return new JsonResponse([]);
    }
}
