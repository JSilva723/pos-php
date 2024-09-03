<?php

declare(strict_types=1);

namespace Tenant\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Tenant\Entity\SaleOrderLine;

/**
 * @extends ServiceEntityRepository<SaleOrderLine>
 */
class SaleOrderLineRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SaleOrderLine::class);
    }

    /**
     * @return array<array<string, mixed>>
     */
    public function getLinesById(int $soid): array
    {
        $query = '
        SELECT p.name, sol.quantity, sol.price, sol.id
        FROM Tenant\Entity\SaleOrderLine sol
        INNER JOIN Tenant\Entity\Product p WITH p.id = sol.product
        WHERE sol.saleOrder = :soid';

        $qb = $this->getEntityManager()->createQuery($query);
        $qb->setParameter('soid', $soid);

        return $qb->getArrayResult();
    }

    public function add(int $pid, int $soid, int $quantity, float $price): void
    {
        $query = '
        INSERT INTO sale_order_line (product_id, sale_order_id, quantity, price)
        VALUES (:pid, :soid, :quantity, :price)';

        // SQL Prepared Statements: Named
        $stmt = $this->getEntityManager()->getConnection()->prepare($query);
        $stmt->bindValue('pid', $pid);
        $stmt->bindValue('soid', $soid);
        $stmt->bindValue('quantity', $quantity);
        $stmt->bindValue('price', $price);
        $stmt->executeQuery();
    }
}
