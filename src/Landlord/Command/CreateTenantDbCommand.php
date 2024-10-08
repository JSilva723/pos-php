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

use function sprintf;

class CreateTenantDbCommand extends Command
{
    protected static string $defaultName = 'landlord:create-tenant-db';

    public function __construct(private readonly Connection $landlordConnection)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Create a new tenant database')
            ->addArgument('tenantDbName', InputArgument::REQUIRED, 'The name of new tenant');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $tenantDbName = $input->getArgument('tenantDbName');

        $sql = sprintf('CREATE DATABASE IF NOT EXISTS `%s`', $tenantDbName);

        try {
            $this->landlordConnection->executeStatement($sql);
            $io->success(sprintf('The database "%s" has been created successfully.', $tenantDbName));
        } catch (Exception $e) {
            $io->error('Error creating database: ' . $e->getMessage());

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
