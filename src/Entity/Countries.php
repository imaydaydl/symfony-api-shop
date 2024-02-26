<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Table(name: "countries")]
#[ORM\Entity]
class Countries
{
    #[ORM\Column(name: "id", type: Types::INTEGER, nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    public ?int $id;

    #[ORM\Column(name: "name", type: Types::STRING, length: 100, nullable: false)]
    #[Assert\NotBlank]
    #[Assert\Length(min: "3", max: "100")]
    public string $name;

    #[ORM\Column(name: "short_name", type: Types::STRING, length: 2, nullable: false)]
    #[Assert\NotBlank]
    #[Assert\Length(min: "2", max: "2")]
    public string $short_name;

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

    public function getShortname(): ?string
    {
        return $this->short_name;
    }

    public function setShortname(string $short_name): self
    {
        $this->short_name = $short_name;

        return $this;
    }

}
