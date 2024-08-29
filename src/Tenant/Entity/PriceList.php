<?php

declare(strict_types=1);

namespace Tenant\Entity;

use Doctrine\ORM\Mapping as ORM;
use Tenant\Repository\PriceListRepository;

#[ORM\Entity(repositoryClass: PriceListRepository::class)]
#[ORM\Table(name: 'price_list')]
class PriceList
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 100)]
    private string $name;

    #[ORM\Column(name: 'is_enable')]
    private bool $isEnable = true;

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
}
