<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Table(name: "countries_vat")]
#[ORM\Entity]
class CountriesVat
{
    #[ORM\Column(name: "id", type: Types::INTEGER, nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    private ?int $id;

    #[ORM\ManyToOne(targetEntity: "Countries")]
    #[ORM\JoinColumn(name: "country", referencedColumnName: "id", onDelete: "CASCADE")]
    public Countries $country;

    #[ORM\ManyToOne(targetEntity: "Products")]
    #[ORM\JoinColumn(name: "product", referencedColumnName: "id", onDelete: "CASCADE")]
    public Products $product;

    #[ORM\Column(name: "vat", type: Types::INTEGER, length: 3, nullable: false)]
    #[Assert\NotBlank]
    #[Assert\Length(min: "1", max: "3")]
    public ?int $vat;

    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function getCountry(): ?Countries
    {
        return $this->country;
    }

    public function setCountry(?Countries $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getProduct(): ?Products
    {
        return $this->product;
    }

    public function setProduct(?Products $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getVat(): ?int
    {
        return $this->vat;
    }

    public function setVat(?int $vat): self
    {
        $this->vat = $vat;

        return $this;
    }

}
