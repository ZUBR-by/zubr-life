<?php

namespace App\Auth;

use App\BotTokenFactory;
use App\Users;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;
use function Psl\Json\encode;

class LoginAction extends AbstractController
{
    public function __invoke(
        Request         $request,
        BotTokenFactory $botTokenFactory,
        string          $domain,
        string          $slug,
        Users           $users,
        LoggerInterface $logger,
        JWTFactory      $JWTFactory,
        string          $community = 'btnu'
    ): Response
    {
        $credentials = $request->query->all();
        $error       = $this->checkCredentials($credentials, $botTokenFactory->current());
        $path        = sprintf('https://%s.zubr.life/', $community);
        $response    = $this->redirect($path);
        if ($error) {
            $logger->error(
                $error->__toString(),
                [
                    'slug'  => $slug,
                    'token' => $botTokenFactory->current(),
                    'query' => $credentials
                ]
            );
            $response->setTargetUrl($path . '?error=auth');
            return $response;
        }
        $referer = (string)$request->headers->get('referer');
        $url     = parse_url($referer);
        if (isset($url['scheme'], $url['host'], $url['path'])) {
            $path = $url['scheme'] . '://' . $url['host'] . $url['path'] . '?';
            syslog(LOG_INFO, $path);
        }
        $response    = $this->redirect($path);
        $id          = $credentials['id'];
        $credentials = [
            'id'     => $id,
            'hasura' => [
                'x-hasura-allowed-roles' => ['life_user'],
                'x-hasura-default-role'  => 'life_user',
                'x-hasura-user-id'       => (string)$credentials['id']
            ],
            'exp'    => time() + 38 * 24 * 60 * 60,
        ];
        $users->add($id);
        $response->headers->setCookie(
            new Cookie(
                'AUTH',
                $JWTFactory->encode($credentials),
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
        if (!isset($credentials['hash'], $credentials['id'])) {
            return new InvalidCredentials('Not found hash or id');
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
