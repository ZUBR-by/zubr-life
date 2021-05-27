<?php

namespace App;

use App\Errors\ExpectedError;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class ErrorListener
{
    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function onKernelException(ExceptionEvent $event) : void
    {
        $exception = $event->getThrowable();

        $response = new JsonResponse();
        $response->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        $event->setResponse($response);
        if ($exception instanceof ExpectedError) {
            $event->stopPropagation();
            $response->setData(['error' => $exception->getMessage()]);

            return;
        }
        $this->logger->error($exception->__toString());
        $response->setData(['error' => 'Ошибка']);
    }
}
