<?php

declare(strict_types=1);

namespace Tenant\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Tenant\Repository\ClientRepository;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
#[ORM\Table(name: 'client')]
class Client
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 100)]
    private string $name;

    #[ORM\Column(name: 'is_enable')]
    private bool $isEnable = true;

    /** @var Collection<int, SaleOrder> */
    #[ORM\OneToMany(targetEntity: SaleOrder::class, mappedBy: 'client')]
    private Collection $saleOrders;

    public function __construct()
    {
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
     * @return Collection<int, SaleOrder>
     */
    public function getSaleOrders(): Collection
    {
        return $this->saleOrders;
    }
}
