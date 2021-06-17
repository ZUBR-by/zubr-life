<?php

namespace App\Auth;

use App\Entity\User;
use App\Users;
use Firebase\JWT\JWT;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use UnexpectedValueException;

class GetUser implements ArgumentValueResolverInterface
{
    private LoggerInterface $logger;
    private string $publicKey;
    private Users $users;

    public function __construct(
        LoggerInterface $logger,
        Users $users,
        string $publicKey
    ) {
        $this->logger    = $logger;
        $this->publicKey = file_get_contents($publicKey);
        $this->users     = $users;
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
                $this->publicKey,
                ['RS256']
            );
        } catch (UnexpectedValueException $e) {
            $request->cookies->remove('AUTH_TOKEN');
            $decoded = ['id' => 0];
        }

        yield $this->users->getById($decoded['id']);
    }
}
