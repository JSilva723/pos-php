<?php

declare(strict_types=1);

namespace Tenant\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use Tenant\Entity\Payment;

/**
 * @extends ServiceEntityRepository<Payment>
 */
class PaymentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Payment::class);
    }

    public function findByQ(string $value): Query
    {
        $query = '
        SELECT p.id, p.name 
        FROM Tenant\Entity\Payment p
        WHERE p.isEnable = true';

        if ($value !== '') {
            $query .= ' AND (p.name LIKE :value) ';
        }

        $query .= ' ORDER BY p.name ASC ';

        $qb = $this->getEntityManager()->createQuery($query);

        if ($value !== '') {
            $qb->setParameter('value', '%' . $value . '%');
        }

        return $qb;
    }

    public function disable(int $id): void
    {
        $query = '
        UPDATE Tenant\Entity\Payment p 
        SET p.isEnable = false 
        WHERE p.id = :id';

        $qb = $this->getEntityManager()->createQuery($query);
        $qb->setParameter('id', $id);

        $qb->execute();
    }
}
