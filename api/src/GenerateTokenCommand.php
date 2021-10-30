<?php

namespace App;

use Firebase\JWT\JWT;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use function Psl\Filesystem\read_file;

class GenerateTokenCommand extends Command
{
    private string $graphPrivateKey;
    private string $graphJwtAlgo;

    public function __construct(string $graphPrivateKey, string $graphJwtAlgo)
    {
        $this->graphPrivateKey = $graphPrivateKey;
        $this->graphJwtAlgo = $graphJwtAlgo;

        parent::__construct('generate:token');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(JWT::encode(
            [
                'hasura' => [
                    'x-hasura-allowed-roles' => ['community_moderator'],
                    'x-hasura-default-role' => 'community_moderator',
                ],
                'exp' => time() + 38 * 24 * 60 * 60,
            ],
            read_file($this->graphPrivateKey),
            $this->graphJwtAlgo
        ));

        return 0;
    }
}
