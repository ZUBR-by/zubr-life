<?php

namespace App;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DisplayWidget extends AbstractController
{
    public function __invoke(Request $request): Response
    {
        return new JsonResponse([]);
    }
}
