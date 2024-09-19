<?php

declare(strict_types=1);

namespace Tenant\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class PriceList
{
    private int $id;
    private string $name;
    private bool $isEnable = true;

    /** @var Collection<int, ProductPriceList> */
    private Collection $productPriceLists;
    /** @var Collection<int, SaleOrder> */
    private Collection $saleOrders;

    public function __construct()
    {
        $this->productPriceLists = new ArrayCollection();
        $this->saleOrders = new ArrayCollection();
        $this->isEnable = true;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getIsEnable(): bool
    {
        return $this->isEnable;
    }

    public function setIsEnable(bool $isEnable): static
    {
        $this->isEnable = $isEnable;

        return $this;
    }

    /**
     * @return Collection<int, ProductPriceList>
     */
    public function getProductPriceLists(): Collection
    {
        return $this->productPriceLists;
    }

    /**
     * @return Collection<int, SaleOrder>
     */
    public function getSaleOrders(): Collection
    {
        return $this->saleOrders;
    }
}
