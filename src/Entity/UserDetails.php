<?php

namespace App\Entity;

use App\Repository\UserDetailsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserDetailsRepository::class)]
class UserDetails
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $first_name = null;

    #[ORM\Column(length: 50)]
    private ?string $last_name = null;

    #[ORM\Column]
    private ?int $age = null;

    #[ORM\Column]
    private ?\DateTime $register_date = null;

    #[ORM\Column]
    private ?\DateTime $last_connection = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): static
    {
        $this->first_name = $first_name;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(string $last_name): static
    {
        $this->last_name = $last_name;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(int $age): static
    {
        $this->age = $age;

        return $this;
    }

    public function getRegisterDate(): ?\DateTime
    {
        return $this->register_date;
    }

    public function setRegisterDate(\DateTime $register_date): static
    {
        $this->register_date = $register_date;

        return $this;
    }

    public function getLastConnection(): ?\DateTime
    {
        return $this->last_connection;
    }

    public function setLastConnection(\DateTime $last_connection): static
    {
        $this->last_connection = $last_connection;

        return $this;
    }
}
