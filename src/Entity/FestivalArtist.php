<?php

namespace App\Entity;

use App\Repository\FestivalArtistRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FestivalArtistRepository::class)]
class FestivalArtist
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Editions $edition_id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Artist $artist_id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Stage $stage_id = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getEditionId(): ?Editions
    {
        return $this->edition_id;
    }

    public function setEditionId(?Editions $edition_id): static
    {
        $this->edition_id = $edition_id;

        return $this;
    }

    public function getArtistId(): ?Artist
    {
        return $this->artist_id;
    }

    public function setArtistId(?Artist $artist_id): static
    {
        $this->artist_id = $artist_id;

        return $this;
    }

    public function getStageId(): ?Stage
    {
        return $this->stage_id;
    }

    public function setStageId(?Stage $stage_id): static
    {
        $this->stage_id = $stage_id;

        return $this;
    }
}
