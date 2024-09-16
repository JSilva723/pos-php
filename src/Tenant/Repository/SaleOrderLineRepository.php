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
    public function getLinesById(int $saleOrderId): array
    {
        $query = '
        SELECT p.id, p.name, p.unitOfMeasure as uom, p.unitOfMeasureForSale as uomfs, sol.quantity, sol.price, sol.id as solid
        FROM Tenant\Entity\SaleOrderLine sol
        INNER JOIN Tenant\Entity\Product p WITH p.id = sol.product
        WHERE sol.saleOrder = :saleOrderId';

        $qb = $this->getEntityManager()->createQuery($query);
        $qb->setParameter('saleOrderId', $saleOrderId);

        return $qb->getArrayResult();
    }

    public function addLine(int $productId, int $saleOrderId, int $quantity, float $price): void
    {
        $query = '
        INSERT INTO sale_order_line (product_id, sale_order_id, quantity, price)
        VALUES (:productId, :saleOrderId, :quantity, :price)';

        // SQL Prepared Statements: Named
        $stmt = $this->getEntityManager()->getConnection()->prepare($query);
        $stmt->bindValue('productId', $productId);
        $stmt->bindValue('saleOrderId', $saleOrderId);
        $stmt->bindValue('quantity', $quantity);
        $stmt->bindValue('price', $price);
        $stmt->executeQuery();
    }

    public function removeLine(int $saleOrderLineId): void
    {
        $query = '
        DELETE FROM sale_order_line
        WHERE id = :saleOrderLineId';

        // SQL Prepared Statements: Named
        $stmt = $this->getEntityManager()->getConnection()->prepare($query);
        $stmt->bindValue('saleOrderLineId', $saleOrderLineId);

        $stmt->executeQuery();
    }
}
