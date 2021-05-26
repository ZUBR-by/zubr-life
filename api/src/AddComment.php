<?php

namespace App;

use App\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class AddComment
{
    public function __invoke(User $user, Request $request) : JsonResponse
    {
        return new JsonResponse([]);
    }
}
