<?php

namespace App\Entity;

use App\Repository\PurchaseRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PurchaseRepository::class)]
class Purchase
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?bool $is_used = null;

    #[ORM\ManyToOne(inversedBy: 'purchases')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user_id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Festival $festival_id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Ticket $ticket_type_id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }


    public function isUsed(): ?bool
    {
        return $this->is_used;
    }

    public function setIsUsed(bool $is_used): static
    {
        $this->is_used = $is_used;

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->user_id;
    }

    public function setUserId(?User $user_id): static
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getFestivalId(): ?Festival
    {
        return $this->festival_id;
    }

    public function setFestivalId(?Festival $festival_id): static
    {
        $this->festival_id = $festival_id;

        return $this;
    }

    public function getTicketTypeId(): ?Ticket
    {
        return $this->ticket_type_id;
    }

    public function setTicketTypeId(Ticket $ticket_type_id): static
    {
        $this->ticket_type_id = $ticket_type_id;

        return $this;
    }

}
