<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OrderRepository::class)
 * @ORM\Table(name="`order`")
 */
class Order
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $orderId;

    /**
     * @ORM\Column(type="object")
     */
    private $customer;

    /**
     * @ORM\Column(type="array")
     */
    private $items = [];

    /**
     * @ORM\Column(type="array")
     */
    private $discounts;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $date;

    /**
     * @ORM\Column(type="float")
     */
    private $shippingPrice;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $totalValue;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $averagePrice;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $distinctUnit;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateISO;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrderId(): ?int
    {
        return $this->orderId;
    }

    public function setOrderId(int $orderId): self
    {
        $this->orderId = $orderId;

        return $this;
    }

    public function getCustomer()
    {
        return $this->customer;
    }

    public function setCustomer($customer): self
    {
        $this->customer = $customer;

        return $this;
    }

    public function getItems(): ?array
    {
        return $this->items;
    }

    public function setItems(array $items): self
    {
        $this->items = $items;

        return $this;
    }

    public function getDiscounts(): ?array
    {
        return $this->discounts;
    }

    public function setDiscounts(?array $discounts): self
    {
        $this->discounts = $discounts;

        return $this;
    }

    public function getDate(): ?string
    {
        return $this->date;
    }

    public function setDate(string $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getShippingPrice(): ?float
    {
        return $this->shippingPrice;
    }

    public function setShippingPrice(float $shippingPrice): self
    {
        $this->shippingPrice = $shippingPrice;

        return $this;
    }

    public function calculateTotalValue() {
        $totalValue = 0;
        foreach ($this->items as $item) {
            $itemTotalPrice = $item -> calculateTotalPrice();
            $totalValue = $totalValue + $itemTotalPrice;
        }

        foreach ($this->discounts as $discount) {
            $discountType = $discount -> getType();
            $discountValue = $discount -> getValue();

            if($discountType == 'PERCENTAGE') {
                $discountedPrice = $totalValue * $discountValue;
                $totalValue = $totalValue - $discountedPrice;
            } else if ($discountType == 'DOLLARS') {
                $totalValue = $totalValue - $discountValue;
            }
        }
        return $totalValue;
    }

    public function getTotalValue(): ?float
    {
        return $this->totalValue;
    }

    public function setTotalValue(?float $totalValue): self
    {
        $this->totalValue = $totalValue;

        return $this;
    }

    public function calculateAveragePrice() {
        $totalUnit = 0;
        foreach ($this->items as $item) {
            $totalUnit = $totalUnit + $item->getQuantity();
        }

        return $this->totalValue / $totalUnit;
    }

    public function getAveragePrice(): ?float
    {
        return $this->averagePrice;
    }

    public function setAveragePrice(?float $averagePrice): self
    {
        $this->averagePrice = $averagePrice;

        return $this;
    }

    public function countDistinctUnit() {
        return count($this->items);
    }

    public function getDistinctUnit(): ?int
    {
        return $this->distinctUnit;
    }

    public function setDistinctUnit(?int $distinctUnit): self
    {
        $this->distinctUnit = $distinctUnit;

        return $this;
    }

    public function getDateISO(): ?\DateTimeInterface
    {
        return $this->dateISO;
    }

    public function setDateISO(\DateTimeInterface $dateISO): self
    {
        $this->dateISO = $dateISO;

        return $this;
    }
}
