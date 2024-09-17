<?php

declare(strict_types=1);

namespace Landlord\Command;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Tenant\Entity\Category;
use Tenant\Entity\Client;
use Tenant\Entity\Payment;
use Tenant\Entity\PriceList;
use Tenant\Entity\User;

use function sprintf;

class LoadInitialDataCommand extends Command
{
    protected static string $defaultName = 'landlord:load-initial-data';

    public function __construct(
        private readonly Connection $landlordConnection,
        private readonly EntityManagerInterface $entityManager,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Load initial data')
            ->addArgument('tenantDbName', InputArgument::REQUIRED, 'The name of new tenant')
            ->addArgument('username', InputArgument::REQUIRED, 'The owner username');
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

        $username = $input->getArgument('username');
        $userRepository = $this->entityManager->getRepository(User::class);
        $existingUser = $userRepository->findOneBy(['username' => $username]);

        if (!$existingUser) {
            $user = new User();
            $user->setUsername($username);
            $user->setRoles(['ROLE_USER']);
            $user->setPassword('$2y$13$O6TxZOXywpOYKmgzc1Zn4uU9dtvCMWlHT1p/8.aFRYn2k7AidSOPO');
            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $output->writeln('User charged successfully.');
        } else {
            $output->writeln('User already exists, no changes were made.');
        }

        $categoryRepository = $this->entityManager->getRepository(Category::class);
        $existingCategory = $categoryRepository->findOneBy(['name' => 'Uncategoryzed']);

        if (!$existingCategory) {
            $category = new Category();
            $category->setName('Uncategoryzed');
            $this->entityManager->persist($category);
            $this->entityManager->flush();

            $output->writeln('Category charged successfully.');
        } else {
            $output->writeln('Category already exists, no changes were made.');
        }

        $priceListRepository = $this->entityManager->getRepository(PriceList::class);
        $existingPriceList = $priceListRepository->findOneBy(['name' => 'Default']);

        if (!$existingPriceList) {
            $priceList = new PriceList();
            $priceList->setName('Default');
            $this->entityManager->persist($priceList);
            $this->entityManager->flush();

            $output->writeln('PriceList charged successfully.');
        } else {
            $output->writeln('PriceList already exists, no changes were made.');
        }

        $paymentRepository = $this->entityManager->getRepository(Payment::class);
        $existingPayment = $paymentRepository->findOneBy(['name' => 'Cash']);

        if (!$existingPayment) {
            $payment = new Payment();
            $payment->setName('Cash');
            $this->entityManager->persist($payment);
            $this->entityManager->flush();

            $output->writeln('Payment charged successfully.');
        } else {
            $output->writeln('Payment already exists, no changes were made.');
        }

        $clientRepository = $this->entityManager->getRepository(Client::class);
        $existingClient = $clientRepository->findOneBy(['name' => 'Final consumer']);

        if (!$existingClient) {
            $client = new Client();
            $client->setName('Final consumer');
            $this->entityManager->persist($client);
            $this->entityManager->flush();

            $output->writeln('Client charged successfully.');
        } else {
            $output->writeln('Client already exists, no changes were made.');
        }

        return Command::SUCCESS;
    }
}
