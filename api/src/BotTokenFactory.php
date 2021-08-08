<?php

namespace App;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class BotTokenFactory
{
    private string $slug;
    private ParameterBagInterface $parameterBag;

    public function __construct(string $slug, ParameterBagInterface $parameterBag)
    {
        $this->slug         = $slug;
        $this->parameterBag = $parameterBag;
    }

    /** @psalm-suppress all */
    public function current() : string
    {
        /** @psalm-suppress all */
        return $this->parameterBag->get($this->slug . 'Token');
    }
}
