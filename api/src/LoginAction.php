<?php

namespace App;

class LoginAction
{
    public function __invoke()
    {
        syslog(
            LOG_INFO,
            json_encode(
                [
                    'r' => $request->request->all(),
                    'q' => $request->query->all(),
                    'c' => $request->getContent()
                ],
                JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
            )
        );
        $auth_data = $request->query->all();
        $telegram  = new Api($_ENV['LOCALITY_BOT_TOKEN']);

        $check_hash = $auth_data['hash'];
        unset($auth_data['hash']);
        unset($auth_data['/login']);
        $data_check_arr = [];
        foreach ($auth_data as $key => $value) {
            $data_check_arr[] = $key . '=' . $value;
        }
        sort($data_check_arr);
        $data_check_string = implode("\n", $data_check_arr);
        $secret_key        = hash('sha256', $_ENV['LOCALITY_BOT_TOKEN'], true);
        $hash              = hash_hmac('sha256', $data_check_string, $secret_key);
        if (strcmp($hash, $check_hash) !== 0) {
            return new JsonResponse([
                'error' => 'Data is NOT from Telegram',
            ]);
        }
        if ((time() - $auth_data['auth_date']) > 86400) {
            return new JsonResponse([
                'error' => 'Data is outdated',
            ]);
        }

        return new \Symfony\Component\HttpFoundation\RedirectResponse('/');
        try {
            $info = $telegram->getChatMember(
                ['chat_id' => $_ENV['LOCALITY_GROUP_ID'], 'user_id' => 1307520449]
            );
        } catch (Throwable $e) {
            return new JsonResponse([
                'error' => $e->getMessage(),
            ]);
        }
        $response = new JsonResponse([
            'auth' => $auth_data,
            'chat' => $info,
        ]);

        return $response;
    }
}
