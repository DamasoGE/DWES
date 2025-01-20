<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_USERNAME', fields: ['username'])]
#[UniqueEntity(fields: ['username'], message: 'There is already an account with this username')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $username = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    /**
     * @var Collection<int, Game>
     */
    #[ORM\OneToMany(targetEntity: Game::class, mappedBy: 'user1', orphanRemoval: true)]
    private Collection $gamesStarted;

    /**
     * @var Collection<int, Game>
     */
    #[ORM\OneToMany(targetEntity: Game::class, mappedBy: 'user2', orphanRemoval: true)]
    private Collection $gamesEnded;

    public function __construct()
    {
        $this->gamesStarted = new ArrayCollection();
        $this->gamesEnded = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection<int, Game>
     */
    public function getGamesStarted(): Collection
    {
        return $this->gamesStarted;
    }

    public function addGamesStarted(Game $gamesStarted): static
    {
        if (!$this->gamesStarted->contains($gamesStarted)) {
            $this->gamesStarted->add($gamesStarted);
            $gamesStarted->setUser0($this);
        }

        return $this;
    }

    public function removeGamesStarted(Game $gamesStarted): static
    {
        if ($this->gamesStarted->removeElement($gamesStarted)) {
            // set the owning side to null (unless already changed)
            if ($gamesStarted->getUser0() === $this) {
                $gamesStarted->setUser0(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Game>
     */
    public function getGamesEnded(): Collection
    {
        return $this->gamesEnded;
    }

    public function addGamesEnded(Game $gamesEnded): static
    {
        if (!$this->gamesEnded->contains($gamesEnded)) {
            $this->gamesEnded->add($gamesEnded);
            $gamesEnded->setUser1($this);
        }

        return $this;
    }

    public function removeGamesEnded(Game $gamesEnded): static
    {
        if ($this->gamesEnded->removeElement($gamesEnded)) {
            // set the owning side to null (unless already changed)
            if ($gamesEnded->getUser1() === $this) {
                $gamesEnded->setUser1(null);
            }
        }

        return $this;
    }
}
