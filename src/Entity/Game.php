<?php

namespace App\Entity;

use App\Repository\GameRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GameRepository::class)]
class Game
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: false)]
    private ?string $name = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $rosterSize = null;

    #[ORM\OneToMany(mappedBy: 'game', targetEntity: Roster::class)]
    private Collection $rosters;

    #[ORM\OneToMany(mappedBy: 'game', targetEntity: Tournament::class)]
    private Collection $tournaments;

    public function __construct()
    {
        $this->rosters = new ArrayCollection();
        $this->tournaments = new ArrayCollection();
    }

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

    public function getRosterSize(): ?int
    {
        return $this->rosterSize;
    }

    public function setRosterSize(int $rosterSize): self
    {
        $this->rosterSize = $rosterSize;

        return $this;
    }

    /**
     * @return Collection<int, Roster>
     */
    public function getRosters(): Collection
    {
        return $this->rosters;
    }

    public function addRoster(Roster $roster): self
    {
        if (!$this->rosters->contains($roster)) {
            $this->rosters->add($roster);
            $roster->setGame($this);
        }

        return $this;
    }

    public function removeRoster(Roster $roster): self
    {
        if ($this->rosters->removeElement($roster)) {
            // set the owning side to null (unless already changed)
            if ($roster->getGame() === $this) {
                $roster->setGame(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->name;
    }

    /**
     * @return Collection<int, Tournament>
     */
    public function getTournaments(): Collection
    {
        return $this->tournaments;
    }

    public function addTournament(Tournament $tournament): self
    {
        if (!$this->tournaments->contains($tournament)) {
            $this->tournaments->add($tournament);
            $tournament->setGame($this);
        }

        return $this;
    }

    public function removeTournament(Tournament $tournament): self
    {
        if ($this->tournaments->removeElement($tournament)) {
            // set the owning side to null (unless already changed)
            if ($tournament->getGame() === $this) {
                $tournament->setGame(null);
            }
        }

        return $this;
    }
}
