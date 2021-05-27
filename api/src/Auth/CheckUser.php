<?php

namespace App\Auth;

use App\TelegramAdapter;
use Firebase\JWT\JWT;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use UnexpectedValueException;

class CheckUser implements EventSubscriberInterface
{
    /**
     * @var TelegramAdapter
     */
    private TelegramAdapter $adapter;
    private string $publicKey;

    public function __construct(TelegramAdapter $adapter, string $publicKey)
    {
        $this->adapter   = $adapter;
        $this->publicKey = file_get_contents($publicKey);
    }

    public function onKernelController(ControllerEvent $event): void
    {
        $controller = $event->getController();

        if (is_array($controller)) {
            $controller = $controller[0];
        }

        if ($controller instanceof ActionNeedAuthorization) {
            $request = $event->getRequest();
            if (! $request->cookies->has('AUTH_TOKEN')) {
                throw new NotAuthorized();
            }
            try {
                $decoded = (array) JWT::decode(
                    (string) $request->cookies->get('AUTH_TOKEN'),
                    $this->publicKey,
                    ['RS256']
                );
                if (! isset($decoded['id'])) {
                    $request->cookies->remove('AUTH_TOKEN');
                    throw new NotAuthorized();
                }
                if (! $this->adapter->isUserInAllowedGroups($decoded['id'])) {
                    throw new NotInGroups();
                }

            } catch (UnexpectedValueException $e) {
                $request->cookies->remove('AUTH_TOKEN');
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
