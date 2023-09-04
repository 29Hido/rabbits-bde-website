<?php

namespace App\Entity;

use App\Repository\TournamentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TournamentRepository::class)]
class Tournament
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'tournaments')]
    private ?Game $game = null;

    #[ORM\ManyToMany(targetEntity: Roster::class, inversedBy: 'tournaments', cascade: ['persist'])]
    private Collection $rosters;

    #[ORM\Column]
    private ?int $cashprize = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $maxUsers = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    public function __construct()
    {
        $this->rosters = new ArrayCollection();
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

    public function getGame(): ?Game
    {
        return $this->game;
    }

    public function setGame(?Game $game): self
    {
        $this->game = $game;

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
        }

        return $this;
    }

    public function removeRoster(Roster $roster): self
    {
        $this->rosters->removeElement($roster);

        return $this;
    }

    public function getCashprize(): ?int
    {
        return $this->cashprize;
    }

    public function setCashprize(int $cashprize): self
    {
        $this->cashprize = $cashprize;

        return $this;
    }

    public function getMaxUsers(): ?int
    {
        return $this->maxUsers;
    }

    public function setMaxUsers(int $maxUsers): self
    {
        $this->maxUsers = $maxUsers;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function __toString(): string
    {
        return $this->name;
    }

    public function playersAmount(): int {
        $sum = 0;
        foreach ($this->rosters as $roster) {
            $sum += $roster->getMembers()->count();
        }
        return $sum;
    }

    public function hasUser(User $user): bool
    {
        foreach($this->getRosters() as $roster) {
            if($roster->getMembers()->contains($user)) {
                return true;
            }
        }

        return false;
    }

    public function hasRoster(Roster $roster): bool
    {
        return $this->rosters->contains($roster);
    }
}
