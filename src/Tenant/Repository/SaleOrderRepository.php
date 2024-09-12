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
    public function getProductsWhitPrice(int $plid): array
    {
        $query = '
        SELECT p.id, p.name, ppl.price
        FROM Tenant\Entity\Product p
        LEFT JOIN Tenant\Entity\ProductPriceList ppl WITH p.id = ppl.product AND ppl.priceList = :plid
        WHERE p.isEnable = true
        ';

        $qb = $this->getEntityManager()->createQuery($query);

        $qb->setParameter('plid', $plid);

        return $qb->getArrayResult();
    }

    public function updatePayment(int $saleOrderId, int $payment): void
    {
        $query = '
        UPDATE Tenant\Entity\SaleOrder so 
        SET so.payment = :payment, so.status = :status
        WHERE so.id = :saleOrderId';

        $qb = $this->getEntityManager()->createQuery($query);
        $qb->setParameter('saleOrderId', $saleOrderId);
        $qb->setParameter('payment', $payment);
        $qb->setParameter('status', SaleOrder::STATUS_SUCCESS);

        $qb->getResult();
    }

    /**
     * @param array<array<string, mixed>> $orderLines
     */
    public function updateStock(array $orderLines): void
    {
        $query = '
        UPDATE Tenant\Entity\Product p
        SET p.stockQuantity = p.stockQuantity - :quantity
        WHERE p.id = :productId';

        $qbBase = $this->getEntityManager()->createQuery($query);

        foreach ($orderLines as $line) {
            $qb = clone $qbBase;

            $qb->setParameter('productId', $line['id']);
            $qb->setParameter('quantity', $line['quantity']);

            $qb->execute();
        }
    }
}
