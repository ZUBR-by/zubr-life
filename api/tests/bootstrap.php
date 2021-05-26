<?php

use App\Kernel;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__) . '/vendor/autoload.php';

if (file_exists(dirname(__DIR__) . '/config/bootstrap.php')) {
    require dirname(__DIR__) . '/config/bootstrap.php';
} elseif (method_exists(Dotenv::class, 'bootEnv')) {
    (new Dotenv())->bootEnv(dirname(__DIR__) . '/.env');
}


$kernel = new Kernel('test', true);
$kernel->boot();

$container   = $kernel->getContainer();
$application = new Application($kernel);
$application->setAutoExit(false);
$output = new \Symfony\Component\Console\Output\ConsoleOutput();
$application->run(new ArrayInput(
    ['command' => 'doctrine:database:create', '--if-not-exists' => true, '-vvv' => true]),
    $output
);
$application->run(new ArrayInput(['command' => 'doctrine:schema:update', '--force' => true, '-vvv' => true]), $output);
$application->run(new ArrayInput(['command' => 'doctrine:fixtures:load', '-n' => true, '-vvv' => true]), $output);



