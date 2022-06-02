<?php

namespace App;

use App\Auth\JWTFactory;
use InvalidArgumentException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
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

    protected function configure(): void
    {
        $this->addArgument('role', InputArgument::REQUIRED);
        $this->addArgument(
            'user_id',
            InputArgument::OPTIONAL,
            'user_id'
        );
        $this->addArgument(
            'count_days',
            InputArgument::OPTIONAL,
            'token lifetime',
            1
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $payload = [
            'hasura' => [
                'x-hasura-allowed-roles' => [$input->getArgument('role')],
                'x-hasura-default-role'  => $input->getArgument('role'),
            ],
            'exp'    => time() + ((int)$input->getArgument('count_days')) * 24 * 60 * 60,
        ];
        if (!is_numeric($input->getArgument('count_days'))) {
            throw new InvalidArgumentException('Provide correct number for count_days');
        }
        if ($input->getArgument('user_id') !== null && !is_numeric($input->getArgument('user_id'))) {
            throw new InvalidArgumentException('Provide correct number for count_days');
        } else {
            $payload['hasura']['x-hasura-user-id'] = $input->getArgument('user_id');
        }
        $output->writeln($this->JWTFactory->encode($payload));

        return 0;
    }
}
