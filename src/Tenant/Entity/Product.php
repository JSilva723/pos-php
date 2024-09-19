<?php

declare(strict_types=1);

namespace Tenant\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Product
{
    private int $id;
    private string $name;
    private string $brand;
    private int $stockQuantity;
    private int $stockMin;
    private bool $isEnable = true;
    private ?string $sku = null;
    private ?string $img = null;
    private ?string $mimeType = null;

    private Category $category;

    /** @var Collection<int, ProductPriceList> */
    private Collection $productPriceLists;
    /** @var Collection<int, SaleOrderLine> */
    private Collection $saleOrderLines;

    public function __construct()
    {
        $this->productPriceLists = new ArrayCollection();
        $this->saleOrderLines = new ArrayCollection();
        $this->isEnable = true;
        $this->stockMin = 1;
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

    public function getBrand(): string
    {
        return $this->brand;
    }

    public function setBrand(string $brand): static
    {
        $this->brand = $brand;

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

    public function getSku(): ?string
    {
        return $this->sku;
    }

    public function setSku(string $sku): static
    {
        $this->sku = $sku;

        return $this;
    }

    public function getStockQuantity(): int
    {
        return $this->stockQuantity;
    }

    public function setStockQuantity(int $stockQuantity): static
    {
        $this->stockQuantity = $stockQuantity;

        return $this;
    }

    public function getStockMin(): int
    {
        return $this->stockMin;
    }

    public function setStockMin(int $stockMin): static
    {
        $this->stockMin = $stockMin;

        return $this;
    }

    public function getCategory(): Category
    {
        return $this->category;
    }

    public function setCategory(Category $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getImg(): ?string
    {
        return $this->img;
    }

    public function setImg(?string $img): static
    {
        $this->img = $img;

        return $this;
    }

    public function getMimeType(): ?string
    {
        return $this->mimeType;
    }

    public function setMimeType(?string $mimeType): static
    {
        $this->mimeType = $mimeType;

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
     * @return Collection<int, SaleOrderLine>
     */
    public function getOrderLines(): Collection
    {
        return $this->saleOrderLines;
    }
}
