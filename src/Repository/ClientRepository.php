<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Client;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Client>
 */
class ClientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Client::class);
    }

    public function findByQ(string $value): Query
    {
        $query = 'SELECT c.id, c.name, c.address FROM App\Entity\Client c';

        if ($value !== '') {
            $query .= ' WHERE (c.name LIKE :value) ';
        }

        $query .= ' ORDER BY c.name ASC ';

        $qb = $this->getEntityManager()->createQuery($query);

        if ($value !== '') {
            $qb->setParameter('value', '%' . $value . '%');
        }

        return $qb;
    }
}
