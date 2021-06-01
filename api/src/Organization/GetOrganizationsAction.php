<?php

namespace App\Organization;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class GetOrganizationsAction extends AbstractController
{
    public function __invoke(Request $request): JsonResponse
    {
        return new JsonResponse([]);
    }
}
