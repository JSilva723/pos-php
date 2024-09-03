<?php

declare(strict_types=1);

namespace Tenant\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use Tenant\Entity\SaleOrder;

/**
 * @extends ServiceEntityRepository<SaleOrder>
 */
class SaleOrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SaleOrder::class);
    }

    public function findByStatus(string $status): Query
    {
        $query = '
        SELECT so.id, so.date
        FROM Tenant\Entity\SaleOrder so';

        if ($status !== '') {
            $query .= ' WHERE (so.status = :status) ';
        }

        $query .= ' ORDER BY so.date ASC ';

        $qb = $this->getEntityManager()->createQuery($query);

        if ($status !== '') {
            $qb->setParameter('status', $status);
        }

        return $qb;
    }

    /**
     * @return array<array<string, mixed>>
     */
    public function getProductsWhitPrice(): array
    {
        $query = '
        SELECT p.id, p.name, ppl.price
        FROM Tenant\Entity\Product p
        LEFT JOIN Tenant\Entity\ProductPriceList ppl WITH p.id = ppl.product
        WHERE p.isEnable = true
        ';

        $qb = $this->getEntityManager()->createQuery($query);

        return $qb->getArrayResult();
    }
}
