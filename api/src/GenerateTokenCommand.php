<?php

namespace App;

use App\Auth\JWTFactory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateTokenCommand extends Command
{
    private JWTFactory $JWTFactory;

    public function __construct(JWTFactory $JWTFactory)
    {
        $this->JWTFactory = $JWTFactory;
        parent::__construct('generate:token');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln($this->JWTFactory->encode([
            'hasura' => [
                'x-hasura-allowed-roles' => ['life_user'],
                'x-hasura-default-role'  => 'life_user',
                'x-hasura-user-id'       => '0'
            ],
            'id'     => 0,
            'exp'    => time() + 38 * 24 * 60 * 60,
        ]));

        return 0;
    }
}
