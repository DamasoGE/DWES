<?php

namespace App\Entity;

use App\Repository\LocationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LocationRepository::class)]
class Location
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $hallway = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $rack = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $shelf = null;

    #[ORM\OneToOne(mappedBy: 'location', cascade: ['persist', 'remove'])]
    private ?Item $item = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHallway(): ?int
    {
        return $this->hallway;
    }

    public function setHallway(int $hallway): static
    {
        $this->hallway = $hallway;

        return $this;
    }

    public function getRack(): ?int
    {
        return $this->rack;
    }

    public function setRack(int $rack): static
    {
        $this->rack = $rack;

        return $this;
    }

    public function getShelf(): ?int
    {
        return $this->shelf;
    }

    public function setShelf(int $shelf): static
    {
        $this->shelf = $shelf;

        return $this;
    }

    public function getItem(): ?Item
    {
        return $this->item;
    }

    public function setItem(Item $item): static
    {
        // set the owning side of the relation if necessary
        if ($item->getLocation() !== $this) {
            $item->setLocation($this);
        }

        $this->item = $item;

        return $this;
    }
}
