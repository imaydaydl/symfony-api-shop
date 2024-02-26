<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Table(name: "users")]
#[ORM\Entity(repositoryClass: UserRepository::class)]
class Users implements UserInterface
{

    #[ORM\Column(name: "id", type: Types::INTEGER, nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    public ?int $id;

    #[ORM\ManyToOne(targetEntity: UserRole::class)]
    #[ORM\JoinColumn(name: "role", referencedColumnName: "id")]
    public UserRole $role;

    #[ORM\Column(name: "name", type: Types::STRING, length: 30, nullable: false)]
    #[Assert\NotBlank]
    #[Assert\Length(min: "3", max: "30")]
    public ?string $name;

    #[ORM\Column(name: "surname", type: Types::STRING, length: 30)]
    #[Assert\NotBlank]
    #[Assert\Length(min: "3", max: "30")]
    private ?string $surname;

    #[ORM\Column(name: "email", type: Types::STRING, length: 50, nullable: false)]
    #[Assert\NotBlank]
    #[Assert\Length(min: "4", max: "50")]
    private ?string $email;

    #[ORM\Column(name: "password", type: Types::STRING, length: 32, nullable: false)]
    #[Assert\NotBlank]
    #[Assert\Length(min: "6", max: "32")]
    private string $password;

    #[ORM\Column(name: "status", type: Types::SMALLINT, nullable: false, options: ["default" => 1])]
    public ?int $status = 1;

    #[ORM\Column(name: "api_token", type: Types::STRING, length: 32, unique: true)]
    private string $apiToken;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRole(): ?UserRole
    {
        return $this->role;
    }

    public function setRole(?UserRole $role): self
    {
        $this->role = $role;

        return $this;
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Returns the identifier for this user (e.g. username or email address).
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles[] = $this->role->getTechName();
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     *
     * @return void
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getApiToken(): ?string
    {
        return $this->apiToken;
    }

    public function setApiToken(string $apiToken): self
    {
        $this->apiToken = $apiToken;

        return $this;
    }

}
