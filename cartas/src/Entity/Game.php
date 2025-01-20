<?php

namespace App\Entity;

use App\Repository\GameRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GameRepository::class)]
class Game
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column (nullable: true)]
    private ?bool $winner = null;

    #[ORM\ManyToOne(inversedBy: 'gamesStarted')]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $user0 = null;

    #[ORM\ManyToOne(inversedBy: 'gamesEnded')]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $user1 = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: true)]
    private ?Card $card0 = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: true)]
    private ?Card $card1 = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isFinished(): ?bool
    {
        return $this->winner;
    }

    public function setFinished(bool $finished): static
    {
        $this->winner = $finished;

        return $this;
    }

    public function getUser0(): ?User
    {
        return $this->user0;
    }

    public function setUser0(?User $user0): static
    {
        $this->user0 = $user0;

        return $this;
    }

    public function getUser1(): ?User
    {
        return $this->user1;
    }

    public function setUser1(?User $user1): static
    {
        $this->user1 = $user1;

        return $this;
    }

    public function getCard0(): ?Card
    {
        return $this->card0;
    }

    public function setCard0(?Card $card0): static
    {
        $this->card0 = $card0;

        return $this;
    }

    public function getCard1(): ?Card
    {
        return $this->card1;
    }

    public function setCard1(?Card $card1): static
    {
        $this->card1 = $card1;

        return $this;
    }
}
