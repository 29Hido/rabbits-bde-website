<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity('email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\Email]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $username = null;

    #[ORM\ManyToMany(targetEntity: Team::class, mappedBy: 'members')]
    private Collection $teams;

    #[ORM\ManyToMany(targetEntity: Roster::class, mappedBy: 'members')]
    private Collection $rosters;

    public function __construct()
    {
        $this->teams = new ArrayCollection();
        $this->rosters = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function addRole(string $role): void {
        $this->roles[] = $role;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function rolesToString() : string {
        $arrayToString = implode(', ', $this->roles); // "ROLE_USER, ROLE_ADMIN, ..."
        return str_replace('ROLE_', '', $arrayToString); // "USER, ADMIN, ..."
    }

    /**
     * @return Collection<int, Team>
     */
    public function getTeams(): Collection
    {
        return $this->teams;
    }

    public function addTeam(Team $team): self
    {
        if (!$this->teams->contains($team)) {
            $this->teams->add($team);
            $team->addMember($this);
        }

        return $this;
    }

    public function removeTeam(Team $team): self
    {
        if ($this->teams->removeElement($team)) {
            $team->removeMember($this);
        }

        return $this;
    }

    public function teamToString() : string {
        $res = "";
        foreach($this->teams->toArray() as $team) {
            $res .= $this->isCaptain($team) ? "" : $team->generateLink() . ", ";
        }
        return empty($res) ? "Aucune équipe" : substr($res, 0, strlen($res)-2);
    }

    public function isCaptain(Team $team) : bool {
        return $team->getCaptain() === $this;
    }

    public function getOwnTeam() : ?Team {
        foreach($this->teams as $team){
            if($this->isCaptain($team)) return $team;
        }
        return null;
    }

    public function isInTeam(Team $team) : bool {
        foreach($this->getTeams() as $_team) {
            if($_team === $team) return true;
        }
        return false;
    }

    public function __toString(): string
    {
        return ucfirst($this->username);
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
            $roster->addMember($this);
        }

        return $this;
    }

    public function removeRoster(Roster $roster): self
    {
        if ($this->rosters->removeElement($roster)) {
            $roster->removeMember($this);
        }

        return $this;
    }
}
