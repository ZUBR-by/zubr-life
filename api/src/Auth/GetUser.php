<?php

namespace App\Auth;

use App\User;
use App\Users;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use UnexpectedValueException;

class GetUser implements ArgumentValueResolverInterface
{
    private Users $users;
    private JWTFactory $JWTFactory;

    public function __construct(Users $users, JWTFactory $JWTFactory)
    {
        $this->users      = $users;
        $this->JWTFactory = $JWTFactory;
    }

    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        return User::class === $argument->getType();
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        try {
            $decoded = $this->JWTFactory->decode((string)$request->cookies->get('AUTH'));
        } catch (UnexpectedValueException) {
            $request->cookies->remove('AUTH');
        }

        yield $this->users->getById(!isset($decoded) ? 0 : $decoded['id']);
    }
}
