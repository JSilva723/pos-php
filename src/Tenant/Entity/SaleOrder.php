<?php

declare(strict_types=1);

namespace Tenant\Entity;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Tenant\Repository\SaleOrderRepository;

#[ORM\Entity(repositoryClass: SaleOrderRepository::class)]
#[ORM\Table(name: 'sale_order')]
class SaleOrder
{
    public const STATUS_SUCCESS = 'SUCCESS';
    public const STATUS_OPEN = 'OPEN';
    public const STATUS_CANCELED = 'CANCELED';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 10)]
    private string $status;

    #[ORM\ManyToOne(targetEntity: Payment::class, inversedBy: 'sale_order')]
    #[ORM\JoinColumn(name: 'payment_id', referencedColumnName: 'id')]
    private ?Payment $payment;

    #[ORM\Column(type: 'datetime')]
    private DateTimeInterface $date;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'sale_order')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    private User $user;

    public function getId(): int
    {
        return $this->id;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getPayment(): ?Payment
    {
        return $this->payment;
    }

    public function setPayment(?Payment $payment): static
    {
        $this->payment = $payment;

        return $this;
    }

    public function getDate(): DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
