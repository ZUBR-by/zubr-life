<?php

namespace App;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use function Psl\Json\decode;
use function Psl\Json\encode;

class TelegramAuthentication extends AbstractGuardAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'api_login';

    private EntityManagerInterface $entityManager;
    private UrlGeneratorInterface $urlGenerator;
    private JWTTokenManagerInterface $JWTManager;
    private string $botToken;

    public function __construct(
        string $botToken,
        EntityManagerInterface $entityManager,
        UrlGeneratorInterface $urlGenerator
    ) {
        $this->entityManager = $entityManager;
        $this->urlGenerator  = $urlGenerator;
        $this->botToken      = $botToken;
    }

    public function supports(Request $request): bool
    {
        return true;
    }

    public function getCredentials(Request $request): array
    {
        syslog(LOG_INFO, encode($request->cookies->get('AUTH_TOKEN')));
        if (self::LOGIN_ROUTE === $request->attributes->get('_route')
            && $request->isMethod('GET')) {
            return $request->query->all();
        }
        if ($request->cookies->has('AUTH_TOKEN')) {
            return decode($request->cookies->get('AUTH_TOKEN'));
        }

        return [];
    }

    public function getUser($credentials, UserProviderInterface $userProvider): ?User
    {
        $id = $credentials['id'] ?? false;
        if (! $id) {
            return null;
        }
        $user = $this->entityManager->getRepository(User::class)->find($id);
        if (! $user) {
            throw new CustomUserMessageAuthenticationException('Email could not be found.');
        }

        return $user;
    }

    public function checkCredentials($credentials, UserInterface $user): bool
    {
        $check_hash = $credentials['hash'];
        unset($credentials['hash']);
        $data_check_arr = [];
        foreach ($credentials as $key => $value) {
            $data_check_arr[] = $key . '=' . $value;
        }
        sort($data_check_arr);
        $data_check_string = implode("\n", $data_check_arr);
        $secret_key        = hash('sha256', $this->botToken, true);
        $hash              = hash_hmac('sha256', $data_check_string, $secret_key);
        if (strcmp($hash, $check_hash) !== 0) {
            throw new Exception('Data is NOT from Telegram');
        }
        if ((time() - $credentials['auth_date']) > 86400) {
            throw new Exception('Data is outdated');
        }
        return true;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey): ?Response
    {
        $isLogin = self::LOGIN_ROUTE === $request->attributes->get('_route')
            && $request->isMethod('GET');
        if (! $isLogin) {
            return null;
        }
        $response = new RedirectResponse(
            'https://new.zubr.life',
            Response::HTTP_FOUND
        );
        $response->headers->setCookie(
            new Cookie(
                'AUTH_TOKEN',
                encode($request->query->all()),
                time() + 86400 * 30,
                '/',
                '.zubr.life',
                true,
                true,
                false,
                'strict'
            )
        );

        return $response;
    }

    public function start(Request $request, AuthenticationException $authException = null): Response
    {
        return new JsonResponse(['message' => 'Authentication Required'], Response::HTTP_UNAUTHORIZED);
    }

    public function supportsRememberMe(): bool
    {
        return false;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return new JsonResponse(['message' => $exception->getMessage()], Response::HTTP_UNAUTHORIZED);
    }
}
