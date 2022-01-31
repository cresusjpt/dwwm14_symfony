<?php

namespace App\Entity;

use App\Repository\CustomerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CustomerRepository::class)]
class Customer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'string', length: 255)]
    private $surname;

    #[ORM\Column(type: 'string', length: 255)]
    private $city;

    #[ORM\Column(type: 'text')]
    private $address;

    #[ORM\Column(type: 'integer')]
    private $pobox;

    #[ORM\OneToMany(mappedBy: 'customer', targetEntity: Order::class)]
    private $pass;

    #[ORM\OneToOne(inversedBy: 'customer', targetEntity: User::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private $etre;

    public function __construct()
    {
        $this->pass = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getPobox(): ?int
    {
        return $this->pobox;
    }

    public function setPobox(int $pobox): self
    {
        $this->pobox = $pobox;

        return $this;
    }

    /**
     * @return Collection|Order[]
     */
    public function getPass(): Collection
    {
        return $this->pass;
    }

    public function addPass(Order $pass): self
    {
        if (!$this->pass->contains($pass)) {
            $this->pass[] = $pass;
            $pass->setCustomer($this);
        }

        return $this;
    }

    public function removePass(Order $pass): self
    {
        if ($this->pass->removeElement($pass)) {
            // set the owning side to null (unless already changed)
            if ($pass->getCustomer() === $this) {
                $pass->setCustomer(null);
            }
        }

        return $this;
    }

    public function getEtre(): ?User
    {
        return $this->etre;
    }

    public function setEtre(User $etre): self
    {
        $this->etre = $etre;

        return $this;
    }
}
