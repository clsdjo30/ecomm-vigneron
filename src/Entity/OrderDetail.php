<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'order_detail')]
class OrderDetail
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Order::class, inversedBy: 'orderDetails')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Order $relatedOrder = null;

    #[ORM\ManyToOne(targetEntity: Product::class, inversedBy: 'orderDetails')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Product $product = null;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $productName = null;

    #[ORM\Column(type: 'integer')]
    private ?int $quantity = null;

    #[ORM\Column(type: 'integer')]
    private ?int $pricePerUnit = null;

    #[ORM\Column(type: 'integer')]
    private ?int $totalPrice = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRelatedOrder(): ?Order
    {
        return $this->relatedOrder;
    }

    public function setRelatedOrder(?Order $relatedOrder): self
    {
        $this->relatedOrder = $relatedOrder;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getProductName(): ?string
    {
        return $this->productName;
    }

    public function setProductName(string $productName): self
    {
        $this->productName = $productName;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getPricePerUnit(): ?int
    {
        return $this->pricePerUnit;
    }

    public function setPricePerUnit(int $pricePerUnit): self
    {
        $this->pricePerUnit = $pricePerUnit;

        return $this;
    }

    /**
     * Get the price per unit in euros (from cents)
     */
    public function getPricePerUnitInEuros(): float
    {
        return $this->pricePerUnit / 100;
    }

    public function getTotalPrice(): ?int
    {
        return $this->totalPrice;
    }

    public function setTotalPrice(int $totalPrice): self
    {
        $this->totalPrice = $totalPrice;

        return $this;
    }

    /**
     * Get the total price in euros (from cents)
     */
    public function getTotalPriceInEuros(): float
    {
        return $this->totalPrice / 100;
    }

    /**
     * Calculate and set the total price based on quantity and price per unit
     */
    public function calculateTotalPrice(): self
    {
        if ($this->quantity !== null && $this->pricePerUnit !== null) {
            $this->totalPrice = $this->quantity * $this->pricePerUnit;
        }

        return $this;
    }
}
