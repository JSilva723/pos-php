<?php

declare(strict_types=1);

namespace Tenant\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use Tenant\Entity\Client;

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
        $query = 'SELECT c.id, c.name, c.address FROM Tenant\Entity\Client c';

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
