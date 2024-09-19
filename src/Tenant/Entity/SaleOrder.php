<?php

declare(strict_types=1);

namespace Tenant\Entity;

use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class SaleOrder
{
    public const STATUS_SUCCESS = 'SUCCESS';
    public const STATUS_OPEN = 'OPEN';
    public const STATUS_CANCELED = 'CANCELED';

    private int $id;
    private string $status;
    private DateTimeInterface $date;

    private User $user;
    private Client $client;
    private PriceList $priceList;
    private ?Payment $payment;

    /** @var Collection<int, SaleOrderLine> */
    private Collection $saleOrderLines;

    public function __construct()
    {
        $this->saleOrderLines = new ArrayCollection();
        $this->date = new DateTime();
        $this->status = self::STATUS_OPEN;
    }

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

    public function getClient(): Client
    {
        return $this->client;
    }

    public function setClient(Client $client): static
    {
        $this->client = $client;

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

    public function getPayment(): ?Payment
    {
        return $this->payment;
    }

    public function setPayment(?Payment $payment): static
    {
        $this->payment = $payment;

        return $this;
    }

    /**
     * @return Collection<int, SaleOrderLine>
     */
    public function getOrderLines(): Collection
    {
        return $this->saleOrderLines;
    }
}
