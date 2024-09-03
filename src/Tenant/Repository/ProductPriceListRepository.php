<?php

declare(strict_types=1);

namespace Tenant\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use Tenant\Entity\ProductPriceList;

/**
 * @extends ServiceEntityRepository<ProductPriceList>
 */
class ProductPriceListRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductPriceList::class);
    }

    public function findByQ(string $value, int $plid): Query
    {
        $query = '
        SELECT p.id, p.name, ppl.price, ppl.id as pplid
        FROM Tenant\Entity\Product p
        LEFT JOIN Tenant\Entity\ProductPriceList ppl WITH p.id = ppl.product AND ppl.priceList = :plid
        WHERE p.isEnable = true';

        if ($value !== '') {
            $query .= ' AND (p.name LIKE :value) ';
        }

        $query .= ' ORDER BY p.name ASC ';

        $qb = $this->getEntityManager()->createQuery($query);

        if ($value !== '') {
            $qb->setParameter('value', '%' . $value . '%');
        }

        $qb->setParameter('plid', $plid);

        return $qb;
    }

    public function create(int $pid, int $lid, float $price): void
    {
        $query = '
        INSERT INTO product_price_list (product_id, price_list_id, price)
        VALUES (:pid, :lid, :price)';

        // SQL Prepared Statements: Named
        $stmt = $this->getEntityManager()->getConnection()->prepare($query);
        $stmt->bindValue('pid', $pid);
        $stmt->bindValue('lid', $lid);
        $stmt->bindValue('price', $price);
        $stmt->executeQuery();
    }
}
