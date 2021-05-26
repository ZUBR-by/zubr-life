<?php

namespace App;

use App\Entity\User;
use App\Errors\NotAuthenticated;
use Firebase\JWT\JWT;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Throwable;

class GetUser implements ArgumentValueResolverInterface
{
    private LoggerInterface $logger;
    private string $publicKey;
    private Users $users;

    public function __construct(LoggerInterface $logger, Users $users, string $publicKey)
    {
        $this->logger    = $logger;
        $this->publicKey = file_get_contents($publicKey);
        $this->users     = $users;
    }

    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        if (User::class !== $argument->getType()) {
            return false;
        }
        if (! $request->cookies->has('AUTH_TOKEN')) {
            throw new NotAuthenticated();
        }

        try {
            $decoded = (array) JWT::decode(
                $request->cookies->get('AUTH_TOKEN'),
                $this->publicKey,
                ['RS256']
            );
            return isset($decoded['id']);
        } catch (Throwable $e) {
            $request->cookies->remove('AUTH_TOKEN');
            throw $e;
        }
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $decoded = (array) JWT::decode(
            $request->cookies->get('AUTH_TOKEN'),
            $this->publicKey,
            ['RS256']
        );
        yield $this->users->getById($decoded['id']);
    }
}
