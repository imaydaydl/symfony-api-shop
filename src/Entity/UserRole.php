<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Table(name: "users_role")]
#[ORM\Entity]
class UserRole
{
    #[ORM\Column(name: "id", type: Types::INTEGER, nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    public ?int $id;

    #[ORM\Column(name: "tech_name", type: Types::STRING, length: 20, nullable: false)]
    private string $techName;

    #[ORM\Column(name: "role_name", type: Types::STRING, length: 20, nullable: false)]
    public string $roleName;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTechName(): ?string
    {
        return $this->techName;
    }

    public function getRoleName(): ?string
    {
        return $this->roleName;
    }

    public function setRoleName(string $roleName): self
    {
        $this->roleName = $roleName;

        return $this;
    }

    public function setTechName(string $techName): self
    {
        $this->techName = $techName;

        return $this;
    }


}
