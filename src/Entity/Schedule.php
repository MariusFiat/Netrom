<?php

namespace App\Entity;

use App\Repository\ScheduleRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ScheduleRepository::class)]
class Schedule
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTime $start_time = null;

    #[ORM\Column]
    private ?\DateTime $end_time = null;

    #[ORM\ManyToOne(inversedBy: 'schedule_id')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Editions $editions_id = null;

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

    public function getStartTime(): ?\DateTime
    {
        return $this->start_time;
    }

    public function setStartTime(\DateTime $start_time): static
    {
        $this->start_time = $start_time;

        return $this;
    }

    public function getEndTime(): ?\DateTime
    {
        return $this->end_time;
    }

    public function setEndTime(\DateTime $end_time): static
    {
        $this->end_time = $end_time;

        return $this;
    }

    public function getEditionsId(): ?Editions
    {
        return $this->editions_id;
    }

    public function setEditionsId(?Editions $editions_id): static
    {
        $this->editions_id = $editions_id;

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
