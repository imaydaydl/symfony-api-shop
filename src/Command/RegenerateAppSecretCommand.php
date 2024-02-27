<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'regenerate-app-secret',
    description: 'Add a short description for your command',
)]
class RegenerateAppSecretCommand extends Command
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $a = '0123456789abcdef';
        $secret = '';
        for ($i = 0; $i < 32; $i++) {
            $secret .= $a[rand(0, 15)];
        }

        $checkCmd = 'grep -E "^APP_SECRET=.{32}$" .env';
        if (shell_exec($checkCmd) !== null) {
            $sedCmd = 'sed -i -E "s/^APP_SECRET=.{32}$/APP_SECRET=' . $secret . '/" .env';
        } else {
            $sedCmd = 'sed -i -E "s/^APP_SECRET=$/APP_SECRET=' . $secret . '/" .env';
        }

        shell_exec($sedCmd);
        
        $io->success('New APP_SECRET was generated: ' . $secret);

        return Command::SUCCESS;
    }
}
