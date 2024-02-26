<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Table(name: "products")]
#[ORM\Entity]
class Products
{
    #[ORM\Column(name: "id", type: Types::INTEGER, nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    public ?int $id;

    #[ORM\Column(name: "name", type: Types::STRING, length: 100, nullable: false)]
    #[Assert\NotBlank]
    #[Assert\Length(min: "3", max: "100")]
    public string $name;

    #[ORM\Column(name: "price", type: Types::DECIMAL, nullable: false)]
    public float $price = 1;

    #[ORM\Column(name: "currency", type: Types::STRING, length: 3, nullable: false)]
    #[Assert\NotBlank]
    #[Assert\Length(min: "3", max: "3")]
    public string $currency;

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

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

}
