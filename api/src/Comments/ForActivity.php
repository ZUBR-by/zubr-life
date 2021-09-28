<?php

namespace App\Comments;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ForActivity extends AbstractController
{
    public function __invoke(Request $request) : JsonResponse
    {
        $content = $request->getContent();

        return new JsonResponse([]);
    }
}
