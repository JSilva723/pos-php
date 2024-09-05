<?php

declare(strict_types=1);

namespace Tenant\Entity;

use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    #[ORM\Column(type: 'datetime')]
    private DateTimeInterface $date;

    #[ORM\ManyToOne(targetEntity: Payment::class, inversedBy: 'saleOrders')]
    #[ORM\JoinColumn(name: 'payment_id', referencedColumnName: 'id')]
    private ?Payment $payment;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'saleOrders')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    private User $user;

    /** @var Collection<int, SaleOrderLine> */
    #[ORM\OneToMany(targetEntity: SaleOrderLine::class, mappedBy: 'saleOrder')]
    private Collection $saleOrderLines;

    #[ORM\ManyToOne(targetEntity: Client::class, inversedBy: 'saleOrders')]
    private ?Client $client;

    #[ORM\ManyToOne(targetEntity: PriceList::class, inversedBy: 'saleOrders')]
    #[ORM\JoinColumn(name: 'price_list_id', referencedColumnName: 'id')]
    private PriceList $priceList;

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

    /**
     * @return Collection<int, SaleOrderLine>
     */
    public function getOrderLines(): Collection
    {
        return $this->saleOrderLines;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): static
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
}
