<?php

declare(strict_types=1);

namespace Tenant\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use Tenant\Entity\PriceList;

/**
 * @extends ServiceEntityRepository<PriceList>
 */
class PriceListRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PriceList::class);
    }

    public function findByQ(string $value): Query
    {
        $query = '
        SELECT c.id, c.name 
        FROM Tenant\Entity\PriceList c
        WHERE c.isEnable = true';

        if ($value !== '') {
            $query .= ' AND (c.name LIKE :value) ';
        }

        $query .= ' ORDER BY c.name ASC ';

        $qb = $this->getEntityManager()->createQuery($query);

        if ($value !== '') {
            $qb->setParameter('value', '%' . $value . '%');
        }

        return $qb;
    }

    public function disable(int $id): void
    {
        $query = '
        UPDATE Tenant\Entity\PriceList c 
        SET c.isEnable = false 
        WHERE c.id = :id';

        $qb = $this->getEntityManager()->createQuery($query);
        $qb->setParameter('id', $id);

        $qb->execute();
    }
}
