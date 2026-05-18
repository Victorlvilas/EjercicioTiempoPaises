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
    name: 'app:saludo',
    description: 'Add a short description for your command',
)]
class SaludoCommand extends Command
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Saluda a una persona')
            ->addArgument('name', InputArgument::REQUIRED, 'Nombre del usuario')
            ->addOption('yell', null, InputOption::VALUE_NONE, 'Mostrar el saludo en mayúsculas')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $name = $input->getArgument('name');
        $yell = $input->getOption('yell');

        $message = 'Hola, %s ! Bienvenido a Symfony.';

        if ($yell) {
            $io->note(sprintf($message, strtoupper($name)));
        } else {
            $io->note(sprintf($message, $name));
        }

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }
}
