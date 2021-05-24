<?php

namespace App;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class TelegramAuthentication extends AbstractGuardAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'api_login';

    private EntityManagerInterface $entityManager;
    private UrlGeneratorInterface $urlGenerator;
    private string $botToken;
    private string $domain;
    private JWTEncoderInterface $encoder;
    private LoggerInterface $logger;

    public function __construct(
        string $botToken,
        string $domain,
        EntityManagerInterface $entityManager,
        UrlGeneratorInterface $urlGenerator,
        JWTEncoderInterface $encoder,
        LoggerInterface $logger
    ) {
        $this->entityManager = $entityManager;
        $this->urlGenerator  = $urlGenerator;
        $this->botToken      = $botToken;
        $this->domain        = $domain;
        $this->encoder       = $encoder;
        $this->logger        = $logger;
    }

    public function supports(Request $request): bool
    {
        return $request->cookies->has('AUTH_TOKEN');
    }

    public function getCredentials(Request $request): array
    {
        if (self::LOGIN_ROUTE === $request->attributes->get('_route')
            && $request->isMethod('GET')) {
            return $request->query->all();
        }
        if (! $request->cookies->has('AUTH_TOKEN')) {
            return [];
        }
        try {
            return $this->encoder->decode($request->cookies->get('AUTH_TOKEN'));
        } catch (JWTDecodeFailureException $e) {
            $this->logger->error($e->__toString());
            $request->cookies->remove('AUTH_TOKEN');
            return [];
        }
    }

    public function getUser($credentials, UserProviderInterface $userProvider): ?User
    {
        $id = $credentials['id'] ?? false;
        if (! $id) {
            return null;
        }
        $user = $this->entityManager->getRepository(User::class)->find($id);
        if (! $user) {
            $user = new User($id);
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }

        return $user;
    }

    public function checkCredentials($credentials, UserInterface $user): bool
    {
        $checkHash = $credentials['hash'];
        unset($credentials['hash']);
        $data = [];
        foreach ($credentials as $key => $value) {
            $data[] = $key . '=' . $value;
        }
        sort($data);
        $secretKey = \Psl\Hash\hash($this->botToken, 'sha256');
        if (strcmp(\Psl\Hash\Hmac\hash(implode("\n", $data), 'sha256', $secretKey), $checkHash) !== 0) {
            throw new Exception('Data is NOT from Telegram');
        }
        if ((time() - $credentials['auth_date']) > 86400) {
            throw new Exception('Data is outdated');
        }
        return true;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey): ?Response
    {
        $isLogin = self::LOGIN_ROUTE === $request->attributes->get('_route') && $request->isMethod('GET');
        if (! $isLogin) {
            return null;
        }
        $response = new RedirectResponse('https://' . $this->domain, Response::HTTP_FOUND);
        $response->headers->setCookie(
            new Cookie(
                'AUTH_TOKEN',
                $this->encoder->encode($request->query->all()),
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

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $request->cookies->remove('AUTH_TOKEN');

        return new JsonResponse(['message' => $exception->getMessage()], Response::HTTP_UNAUTHORIZED);
    }
}
