<?php

namespace App\Entity;

use App\Repository\ArtistRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Entity\Editions;

#[ORM\Entity(repositoryClass: ArtistRepository::class)]
class Artist
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\Column(length: 30)]
    private ?string $music_genre = null;

    #[ORM\ManyToMany(targetEntity: Editions::class, mappedBy: 'lineup')]
    private ?Collection $editions;

    public function __construct()
    {
        $this->editions = new ArrayCollection();
    }

    public function getEditions(): Collection
    {
        return $this->editions;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getMusicGenre(): ?string
    {
        return $this->music_genre;
    }

    public function setMusicGenre(string $music_genre): static
    {
        $this->music_genre = $music_genre;

        return $this;
    }
}
