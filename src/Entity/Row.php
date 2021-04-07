<?php

namespace App\Entity;

use App\Repository\RowRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RowRepository::class)
 */
class Row
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="rows2")
     */
    private $product;

    /**
     * @ORM\ManyToOne(targetEntity=Order::class, inversedBy="rows2")
     */
    private $order2;

    /**
     * @ORM\Column(type="integer")
     */
    private $amount;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduct(): ?product
    {
        return $this->product;
    }

    public function setProduct(?product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getOrder2(): ?Order
    {
        return $this->order2;
    }

    public function setOrder2(?Order $order2): self
    {
        $this->order2 = $order2;

        return $this;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): self
    {
        $this->amount = $amount;

        return $this;
    }
}
