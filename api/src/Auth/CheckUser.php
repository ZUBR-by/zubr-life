<?php

namespace App\Auth;

use App\Entity\User;
use App\TelegramAdapter;
use Doctrine\ORM\EntityManagerInterface;
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
    private string $publicKeyPath;
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $em;

    public function __construct(TelegramAdapter $adapter, string $publicKey, EntityManagerInterface $em)
    {
        $this->adapter       = $adapter;
        $this->publicKeyPath = $publicKey;
        $this->em            = $em;
    }

    public function onKernelController(ControllerEvent $event) : void
    {
        $controller = $event->getController();

        if (is_array($controller)) {
            $controller = $controller[0];
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
                if (! $this->adapter->isUserInAllowedGroups($decoded['id'])) {
                    throw new NotInGroups();
                }
                /** @var User|null $user */
                $user = $this->em->getRepository(User::class)->find($decoded['id']);
                if (! $user) {
                    return;
                }
                if ($user->isBanned()) {
                    throw new Banned();
                }

            } catch (UnexpectedValueException $e) {
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
