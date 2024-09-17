<?php

declare(strict_types=1);

namespace Tenant\Entity;

use Doctrine\ORM\Mapping as ORM;
use Tenant\Repository\SaleOrderLineRepository;

#[ORM\Entity(repositoryClass: SaleOrderLineRepository::class)]
#[ORM\Table(name: 'sale_order_line')]
class SaleOrderLine
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\ManyToOne(targetEntity: Product::class, inversedBy: 'saleOrderLines')]
    #[ORM\JoinColumn(name: 'product_id', referencedColumnName: 'id')]
    private Product $product;

    #[ORM\ManyToOne(targetEntity: SaleOrder::class, inversedBy: 'saleOrderLines')]
    #[ORM\JoinColumn(name: 'sale_order_id', referencedColumnName: 'id')]
    private SaleOrder $saleOrder;

    #[ORM\Column]
    private string $quantity;

    #[ORM\Column(type: 'decimal', scale: 2, precision: 14)]
    private string $price;

    #[ORM\Column(name: 'unit_of_measure', length: 15, options: ['default' => null], nullable: true)]
    private ?string $unitOfMeasure = null;

    public function getId(): int
    {
        return $this->id;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function setProduct(Product $product): static
    {
        $this->product = $product;

        return $this;
    }

    public function getPriceList(): SaleOrder
    {
        return $this->saleOrder;
    }

    public function setPriceList(SaleOrder $saleOrder): static
    {
        $this->saleOrder = $saleOrder;

        return $this;
    }

    public function getQuantity(): string
    {
        return $this->quantity;
    }

    public function setQuantity(string $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getPrice(): string
    {
        return $this->price;
    }

    public function setPrice(string $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getUnitOfMeasure(): ?string
    {
        return $this->unitOfMeasure;
    }

    public function setUnitOfMeasure(?string $unitOfMeasure): static
    {
        $this->unitOfMeasure = $unitOfMeasure;

        return $this;
    }
}
