<?php

namespace App\Errors;

use Exception;
use Psr\Log\LoggerInterface;
use function Psl\Json\encode;

class GraphQLRequestError extends Exception
{
    private array $variables;
    private array $errors;

    public function __construct(array $errors, array $variables)
    {
        $this->variables = $variables;
        $this->errors    = $errors;
        parent::__construct(encode($errors));
    }

    public function log(LoggerInterface $logger) : void
    {
        $logger->error($this->__toString(), $this->variables);
    }

    public function isFirstMessageEqualTo(string $message) : bool
    {
        return $this->errors[0]['message'] === $message;
    }
}
