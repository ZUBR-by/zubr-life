<?php

namespace App\Rating;

use App\Auth\ActionRequiresAuthorization;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class UnratePerson extends AbstractController implements ActionRequiresAuthorization
{
    public function __invoke(Request $request)
    {
        return new JsonResponse(['rating' => 0, 'upvotes' => 0, 'downvotes' => 0]);
    }
}
