<?php

namespace App\Entity;

use App\Repository\TeamRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TeamRepository::class)]
class Team
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToOne(cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $captain = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $creationDate = null;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'teams')]
    private Collection $members;

    #[ORM\OneToMany(mappedBy: 'team', targetEntity: Roster::class, cascade: ['persist', 'remove'] )]
    private Collection $rosters;

    #[ORM\OneToMany(mappedBy: 'team', targetEntity: TeamInvitation::class)]
    private Collection $invitations;

    public function __construct()
    {
        $this->members = new ArrayCollection();
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

    public function getCaptain(): ?User
    {
        return $this->captain;
    }

    public function setCaptain(User $captain): self
    {
        $this->captain = $captain;

        return $this;
    }

    public function getCreationDate(): ?\DateTimeImmutable
    {
        return $this->creationDate;
    }

    public function setCreationDate(\DateTimeImmutable $creationDate): self
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getMembers(): Collection
    {
        return $this->members;
    }

    public function addMember(User $member): self
    {
        if (!$this->members->contains($member)) {
            $this->members->add($member);
        }

        return $this;
    }

    public function removeMember(User $member): self
    {
        $this->members->removeElement($member);

        return $this;
    }

    public function generateLink() : string {
        return "<a href='/team/view/".$this->id."'>". $this->name ."</a>";
    }

    public function __toString(): string
    {
        return $this->name;
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
            $roster->setTeam($this);
        }

        return $this;
    }

    public function removeRoster(Roster $roster): self
    {
        if ($this->rosters->removeElement($roster)) {
            // set the owning side to null (unless already changed)
            if ($roster->getTeam() === $this) {
                $roster->setTeam(null);
            }
        }

        return $this;
    }

    public function getRoster(Game $game) : ?Roster {
        foreach($this->rosters as $roster) {
            if($roster->getGame()->getName() === $game->getName()) {
                return $roster;
            }
        }
        return null;
    }

    /**
     * @return Collection<int, TeamInvitation>
     */
    public function getInvitations(): Collection
    {
        return $this->invitations;
    }

    public function addInvitation(TeamInvitation $invitation): self
    {
        if (!$this->invitations->contains($invitation)) {
            $this->invitations->add($invitation);
            $invitation->setTeam($this);
        }

        return $this;
    }

    public function removeInvitation(TeamInvitation $invitation): self
    {
        if ($this->invitations->removeElement($invitation)) {
            // set the owning side to null (unless already changed)
            if ($invitation->getTeam() === $this) {
                $invitation->setTeam(null);
            }
        }

        return $this;
    }
}
