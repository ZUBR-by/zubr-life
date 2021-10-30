<?php

namespace App\Auth;

use App\TelegramAdapter;
use App\Users;
use Firebase\JWT\JWT;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use UnexpectedValueException;

class CheckUser implements EventSubscriberInterface
{
    private string $publicKeyPath;
    private string $accessToken;
    private Users $users;

    public function __construct(
        string $jwtPublicKey,
        Users $users,
        string $accessToken
    ) {
        $this->publicKeyPath = $jwtPublicKey;
        $this->accessToken   = $accessToken;
        $this->users         = $users;
    }

    public function onKernelController(ControllerEvent $event) : void
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
            if (! $request->cookies->has('AUTH_TOKEN')) {
                throw new NotAuthorized();
            }
            try {
                $decoded = (array) JWT::decode(
                    (string) $request->cookies->get('AUTH_TOKEN'),
                    file_get_contents($this->publicKeyPath),
                    ['RS256']
                );
                if (! isset($decoded['id'])) {
                    $request->cookies->remove('AUTH_TOKEN');
                    throw new NotAuthorized();
                }
                $user = $this->users->getById((int) $decoded['id']);
                if ($user->isEmpty()) {
                    return;
                }
                if ($user->isBanned()) {
                    throw new Banned();
                }

            } catch (UnexpectedValueException) {
                $request->cookies->remove('AUTH_TOKEN');
                throw new NotAuthorized();
            }
        }
    }

    public static function getSubscribedEvents() : array
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }
}
