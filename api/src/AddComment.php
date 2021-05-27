<?php

namespace App;

use App\Auth\ActionNeedAuthorization;
use App\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class AddComment implements ActionNeedAuthorization
{
    public function __invoke(User $user, Request $request): JsonResponse
    {
        return new JsonResponse([]);
    }
}
