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

    #[ORM\Column]
    private ?int $edition_id = null;

    #[ORM\Column]
    private ?int $artist_id = null;

    #[ORM\Column]
    private ?int $stage_id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getEditionId(): ?int
    {
        return $this->edition_id;
    }

    public function setEditionId(int $edition_id): static
    {
        $this->edition_id = $edition_id;

        return $this;
    }

    public function getArtistId(): ?int
    {
        return $this->artist_id;
    }

    public function setArtistId(int $artist_id): static
    {
        $this->artist_id = $artist_id;

        return $this;
    }

    public function getStageId(): ?int
    {
        return $this->stage_id;
    }

    public function setStageId(int $stage_id): static
    {
        $this->stage_id = $stage_id;

        return $this;
    }
}
