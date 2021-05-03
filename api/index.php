<?php

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;
use Telegram\Bot\Api;

require __DIR__ . '/vendor/autoload.php';

(new Dotenv())->load(__DIR__ . '/.env');

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    public function registerBundles() : array
    {
        return [
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
        ];
    }

    protected function configureContainer(ContainerConfigurator $c) : void
    {
        $c->extension('framework', ['secret' => 'S0ME_SECRET']);
    }

    protected function configureRoutes(RoutingConfigurator $routes) : void
    {
        $routes->add('login', '/login')->controller([$this, 'login']);
        $routes->add('index', '/')->controller([$this, 'index']);
    }

    public function index() : JsonResponse
    {
        return new JsonResponse([]);
    }

    public function login(Request $request) : JsonResponse
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

$kernel   = new Kernel($_ENV['APP_ENV'], $_ENV['APP_ENV'] === 'dev');
$request  = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
