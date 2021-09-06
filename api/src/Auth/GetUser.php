<?php

namespace App\Auth;

use App\User;
use App\Users;
use Firebase\JWT\JWT;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use UnexpectedValueException;

class GetUser implements ArgumentValueResolverInterface
{
    private string $jwtPublicKey;
    private Users $users;

    public function __construct(
        Users $users,
        string $jwtPublicKey
    ) {
        $this->jwtPublicKey = file_get_contents($jwtPublicKey);
        $this->users        = $users;
    }

    public function supports(Request $request, ArgumentMetadata $argument) : bool
    {
        return User::class === $argument->getType();
    }

    public function resolve(Request $request, ArgumentMetadata $argument) : iterable
    {
        try {
            $decoded = (array) JWT::decode(
                (string) $request->cookies->get('AUTH_TOKEN'),
                $this->jwtPublicKey,
                ['RS256']
            );
        } catch (UnexpectedValueException $e) {
            $request->cookies->remove('AUTH_TOKEN');
        }

        yield ! isset($decoded) ? new User() : $this->users->getById($decoded['id']);
    }
}
