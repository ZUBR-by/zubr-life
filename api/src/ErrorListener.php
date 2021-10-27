<?php

namespace App;

use App\Auth\NotAuthorized;
use App\Errors\ExpectedError;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use function Psl\Json\encode;

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

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof NotAuthorized) {
            $event->allowCustomResponseCode();
            $event->setResponse(
                new JsonResponse(
                    ['message' => 'not_authorized'],
                    Response::HTTP_UNAUTHORIZED
                )
            );
            $event->stopPropagation();

            return;
        }
        $response = new JsonResponse();
        $event->setResponse($response);
        if ($exception instanceof ExpectedError) {
            $event->stopPropagation();
            $response->setJson(encode(['error' => $exception->getMessage()]));

            return;
        }
        $this->logger->error($exception->__toString());
        $response->setJson(encode(['error' => 'Ошибка']));
    }
}
