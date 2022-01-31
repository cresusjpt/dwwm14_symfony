<?php

namespace App\Entity;

use App\Repository\DetailRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DetailRepository::class)]
class Detail
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'integer')]
    private $qte;

    #[ORM\Column(type: 'float')]
    private $amount;

    #[ORM\ManyToOne(targetEntity: Product::class, inversedBy: 'details')]
    #[ORM\JoinColumn(nullable: false)]
    private $detailProduct;

    #[ORM\ManyToOne(targetEntity: Order::class, inversedBy: 'details')]
    #[ORM\JoinColumn(nullable: false)]
    private $detailOrder;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQte(): ?int
    {
        return $this->qte;
    }

    public function setQte(int $qte): self
    {
        $this->qte = $qte;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getDetailProduct(): ?Product
    {
        return $this->detailProduct;
    }

    public function setDetailProduct(?Product $detailProduct): self
    {
        $this->detailProduct = $detailProduct;

        return $this;
    }

    public function getDetailOrder(): ?Order
    {
        return $this->detailOrder;
    }

    public function setDetailOrder(?Order $detailOrder): self
    {
        $this->detailOrder = $detailOrder;

        return $this;
    }
}
