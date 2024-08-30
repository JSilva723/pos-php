<?php

declare(strict_types=1);

namespace Tenant\Entity;

use Doctrine\ORM\Mapping as ORM;
use Tenant\Repository\ProductPriceListRepository;

#[ORM\Entity(repositoryClass: ProductPriceListRepository::class)]
#[ORM\Table(name: 'product_price_list')]
class ProductPriceList
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\ManyToOne(targetEntity: Product::class, inversedBy: 'product_price_list')]
    #[ORM\JoinColumn(name: 'product_id', referencedColumnName: 'id')]
    private Product $product;

    #[ORM\ManyToOne(targetEntity: PriceList::class, inversedBy: 'product_price_list')]
    #[ORM\JoinColumn(name: 'price_list_id', referencedColumnName: 'id')]
    private PriceList $priceList;

    #[ORM\Column(type: 'decimal', scale: 2, precision: 14)]
    private float $price;

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

    public function getPriceList(): PriceList
    {
        return $this->priceList;
    }

    public function setPriceList(PriceList $priceList): static
    {
        $this->priceList = $priceList;

        return $this;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }
}
