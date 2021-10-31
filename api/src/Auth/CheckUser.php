<?php

namespace App\Auth;

use App\Users;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use UnexpectedValueException;

class CheckUser implements EventSubscriberInterface
{
    private string $accessToken;
    private Users $users;
    private JWTFactory $JWTFactory;

    public function __construct(
        Users      $users,
        JWTFactory $JWTFactory,
        string     $accessToken
    )
    {
        $this->accessToken = $accessToken;
        $this->users       = $users;
        $this->JWTFactory  = $JWTFactory;
    }

    public function onKernelController(ControllerEvent $event): void
    {
        $controller = $event->getController();

        if (is_array($controller)) {
            $controller = $controller[0];
        }
        if ($controller instanceof BotAuthentication) {
            $request = $event->getRequest();
            if ($request->headers->get('Authorization') !== 'Bearer ' . $this->accessToken) {
                throw new NotAuthorized();
            }
        }
        if ($controller instanceof ActionRequiresAuthorization) {
            $request = $event->getRequest();
            if (!$request->cookies->has('AUTH')) {
                throw new NotAuthorized();
            }
            try {
                $decoded = $this->JWTFactory->decode((string)$request->cookies->get('AUTH'));
                if (!isset($decoded['id'])) {
                    $request->cookies->remove('AUTH');
                    throw new NotAuthorized();
                }
                $user = $this->users->getById((int)$decoded['id']);
                if ($user->isEmpty()) {
                    return;
                }
                if ($user->isBanned()) {
                    throw new Banned();
                }

            } catch (UnexpectedValueException) {
                $request->cookies->remove('AUTH');
                throw new NotAuthorized();
            }
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }
}
