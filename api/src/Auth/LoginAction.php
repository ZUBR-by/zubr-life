<?php

namespace App\Auth;

use App\Entity\User;
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
        string $publicKey,
        EntityManagerInterface $em,
        LoggerInterface $logger,
        string $privateKey
    ) : Response {
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

    public function checkCredentials(array $credentials, string $botToken) : ?Throwable
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
        $data_check_string = implode("\n", $data);

        $secretKey = hash('sha256', $botToken, true);
        $hash      = hash_hmac('sha256', $data_check_string, $secretKey);
        if (strcmp($hash, $checkHash) !== 0) {
            return new InvalidCredentials(
                sprintf('Data is NOT from Telegram expected: %s, actual %s', $checkHash, $hash)
            );
        }
        if ((time() - $credentials['auth_date']) > 86400) {
            return new InvalidCredentials('Data is outdated');
        }

        return null;
    }
}
