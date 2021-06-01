<?php

namespace App\Person;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class GetPeopleAction extends AbstractController
{
    public function __invoke(Request $request): JsonResponse
    {
        return new JsonResponse([]);
    }
}
