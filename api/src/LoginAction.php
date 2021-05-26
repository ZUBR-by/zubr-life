<?php

namespace App;

use App\Entity\User;
use App\Errors\InvalidCredentials;
use Doctrine\ORM\EntityManagerInterface;
use Firebase\JWT\JWT;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class LoginAction extends AbstractController
{
    public function __invoke(
        Request $request,
        string $botToken,
        string $domain,
        EntityManagerInterface $em,
        LoggerInterface $logger,
        string $privateKey
    ): Response {
        $credentials = $request->query->all();
        $response    = new RedirectResponse('https://' . $domain, Response::HTTP_FOUND);
        $error       = $this->checkCredentials($credentials, $botToken);
        if ($error) {
            $logger->error($error->__toString());
            $response->setTargetUrl('https://' . $domain . '?error=auth');
            return $response;
        }
        $user = $em->getRepository(User::class)->find($credentials['id']);
        if (! $user) {
            $user = new User($credentials['id']);
            $em->persist($user);
            $em->flush();
        }
        $response->headers->setCookie(
            new Cookie(
                'AUTH_TOKEN',
                JWT::encode($credentials, file_get_contents($privateKey), 'RS256'),
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

    public function checkCredentials(array $credentials, string $botToken): ?Throwable
    {
        if (! isset($credentials['hash'], $credentials['id'])) {
            return new InvalidCredentials();
        }
        $checkHash = $credentials['hash'];
        unset($credentials['hash']);
        $data = [];
        foreach ($credentials as $key => $value) {
            $data[] = $key . '=' . $value;
        }
        sort($data);
        $secretKey = \Psl\Hash\hash($botToken, 'sha256');
        if (strcmp(\Psl\Hash\Hmac\hash(implode("\n", $data), 'sha256', $secretKey), $checkHash) !== 0) {
            return new InvalidCredentials('Data is NOT from Telegram');
        }
        if ((time() - $credentials['auth_date']) > 86400) {
            return new InvalidCredentials('Data is outdated');
        }

        return null;
    }
}
