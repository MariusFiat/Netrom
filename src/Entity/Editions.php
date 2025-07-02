<?php

namespace App\Entity;

use App\Repository\EditionsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EditionsRepository::class)]
class Editions
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $start_date = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $end_date = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Festival $festival_id = null;

    /**
     * @var Collection<int, Schedule>
     */
    #[ORM\OneToMany(targetEntity: Schedule::class, mappedBy: 'editions_id', orphanRemoval: true)]
    private Collection $schedule_id;

    #[ORM\ManyToMany(targetEntity: Artist::class, inversedBy: 'editions')]
    #[ORM\JoinTable(name: 'edition_artist')]
    private ?Collection $lineup;

    public function getLineup(): Collection
    {
        return $this->lineup;
    }

    public function addArtist(Artist $artist): static
    {
        if (!$this->lineup->contains($artist)) {
            $this->lineup->add($artist);
        }

        return $this;
    }

    public function removeArtist(Artist $artist): static
    {
        $this->lineup->removeElement($artist);
        return $this;
    }

    public function addLineup(Artist $artist): self
    {
        if (!$this->lineup->contains($artist)) {
            $this->lineup[] = $artist;
        }
        return $this;
    }

    public function setLineup(iterable $artists): self
    {
        // Clear the current collection
        $this->lineup->clear();

        // Add only the selected artists
        foreach ($artists as $artist) {
            $this->addLineup($artist);
        }

        return $this;
    }
    public function removeLineup(Artist $artist): self
    {
        $this->lineup->removeElement($artist);
        return $this;
    }


    public function __construct()
    {
        $this->schedule_id = new ArrayCollection();
        $this->lineup = new ArrayCollection();
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

    public function getStartDate(): ?\DateTime
    {
        return $this->start_date;
    }

    public function setStartDate(\DateTime $start_date): static
    {
        $this->start_date = $start_date;

        return $this;
    }

    public function getEndDate(): ?\DateTime
    {
        return $this->end_date;
    }

    public function setEndDate(\DateTime $end_date): static
    {
        $this->end_date = $end_date;

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

    /**
     * @return Collection<int, Schedule>
     */
    public function getScheduleId(): Collection
    {
        return $this->schedule_id;
    }

    public function addScheduleId(Schedule $scheduleId): static
    {
        if (!$this->schedule_id->contains($scheduleId)) {
            $this->schedule_id->add($scheduleId);
            $scheduleId->setEditionsId($this);
        }

        return $this;
    }

    public function removeScheduleId(Schedule $scheduleId): static
    {
        if ($this->schedule_id->removeElement($scheduleId)) {
            // set the owning side to null (unless already changed)
            if ($scheduleId->getEditionsId() === $this) {
                $scheduleId->setEditionsId(null);
            }
        }

        return $this;
    }
}
