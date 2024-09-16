<?php

declare(strict_types=1);

namespace Tenant\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Tenant\Config\UnitOfMeasure;
use Tenant\Config\UnitOfMeasureForSale;
use Tenant\Repository\ProductRepository;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[ORM\Table(name: 'product')]
class Product
{
    public const SUBUNITS = [
        UnitOfMeasure::KILOGRAM->value => UnitOfMeasureForSale::GRAM,
        UnitOfMeasure::LITER->value => UnitOfMeasureForSale::MILILITER,
        UnitOfMeasure::METER->value => UnitOfMeasureForSale::CENTIMETER,
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 100)]
    private string $name;

    #[ORM\Column(length: 100, nullable: true)]
    private string $brand;

    #[ORM\Column(name: 'is_enable')]
    private bool $isEnable = true;

    #[ORM\Column(length: 100, unique: true, nullable: true)]
    private ?string $sku = null;

    #[ORM\Column(name: 'stock_quantity')]
    private int $stockQuantity;

    #[ORM\Column(name: 'stock_min')]
    private int $stockMin;

    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: 'products')]
    private Category $category;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $img = null;

    #[ORM\Column(name: 'mime_type', nullable: true)]
    private ?string $mimeType = null;

    /** @var Collection<int, ProductPriceList> */
    #[ORM\OneToMany(targetEntity: ProductPriceList::class, mappedBy: 'product')]
    private Collection $productPriceLists;

    /** @var Collection<int, SaleOrderLine> */
    #[ORM\OneToMany(targetEntity: SaleOrderLine::class, mappedBy: 'product')]
    private Collection $saleOrderLines;

    private string $price;

    private PriceList $priceList;

    #[ORM\Column(name: 'unit_of_measure')]
    private UnitOfMeasure $unitOfMeasure;

    #[ORM\Column(name: 'unit_of_measure_for_sale', options: ['default' => null], nullable: true)]
    private ?UnitOfMeasureForSale $unitOfMeasureForSale = null;

    public function __construct()
    {
        $this->productPriceLists = new ArrayCollection();
        $this->saleOrderLines = new ArrayCollection();
        $this->isEnable = true;
        $this->stockMin = 1;
        $this->unitOfMeasure = UnitOfMeasure::UNIT;
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

    public function getPrice(): string
    {
        return $this->price;
    }

    public function setPrice(string $price): static
    {
        $this->price = $price;

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

    public function getUnitOfMeasure(): UnitOfMeasure
    {
        return $this->unitOfMeasure;
    }

    public function setUnitOfMeasure(UnitOfMeasure $unitOfMeasure): static
    {
        $this->unitOfMeasure = $unitOfMeasure;

        return $this;
    }

    public function getUnitOfMeasureForSale(): ?UnitOfMeasureForSale
    {
        return $this->unitOfMeasureForSale;
    }

    public function setUnitOfMeasureForSale(?UnitOfMeasure $unitOfMeasure): static
    {
        if ($unitOfMeasure) {
            $this->unitOfMeasureForSale = self::SUBUNITS[$unitOfMeasure->value];
        } else {
            $this->unitOfMeasureForSale = null;
        }

        return $this;
    }
}
