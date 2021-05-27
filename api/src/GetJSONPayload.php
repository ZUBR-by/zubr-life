<?php

namespace App;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use function Psl\Json\decode;

class GetJSONPayload implements ArgumentValueResolverInterface
{

    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        return 'array' === $argument->getType() && $argument->getName() === 'payload';
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        /** @psalm-suppress PossiblyInvalidArgument */
        yield decode($request->getContent());
    }
}
