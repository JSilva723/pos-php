<?php

declare(strict_types=1);

namespace Landlord\Command;

use Doctrine\DBAL\Connection;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

use function sprintf;

class RunMigrationsCommand extends Command
{
    protected static string $defaultName = 'landlord:run-migrations';

    public function __construct(private readonly Connection $landlordConnection)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Run migrations in tenant database')
            ->addArgument('tenantDbName', InputArgument::REQUIRED, 'The name of tenant');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $tenantDbName = $input->getArgument('tenantDbName');

        $sql = sprintf('USE `%s`', $tenantDbName);

        try {
            $this->landlordConnection->executeStatement($sql);
            $io->success(sprintf('Change to database "%s".', $tenantDbName));
        } catch (Exception $e) {
            $io->error('Error changind database: ' . $e->getMessage());

            return Command::FAILURE;
        }

        $io->section('Running migrations...');
        $migrateProcess = new Process(['php', 'bin/console', 'doctrine:migrations:migrate', '--no-interaction']);
        $migrateProcess->run();

        if (!$migrateProcess->isSuccessful()) {
            throw new ProcessFailedException($migrateProcess);
        }

        $io->success('Migrations executed successfully.');

        return Command::SUCCESS;
    }
}
