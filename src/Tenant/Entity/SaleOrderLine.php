<?php

declare(strict_types=1);

namespace Tenant\Entity;

class SaleOrderLine
{
    private int $id;
    private float $quantity;
    private string $price;

    private Product $product;
    private SaleOrder $saleOrder;

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

    public function getQuantity(): float
    {
        return $this->quantity;
    }

    public function setQuantity(float $quantity): static
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
}
