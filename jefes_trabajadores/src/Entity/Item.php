<?php

namespace App\Entity;

use App\Repository\ItemRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ItemRepository::class)]
class Item
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column]
    private ?int $amount = null;

    /**
     * @var Collection<int, StockChange>
     */
    #[ORM\OneToMany(targetEntity: StockChange::class, mappedBy: 'item')]
    private Collection $stockChanges;

    #[ORM\OneToOne(inversedBy: 'item', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Location $location = null;

    public function __construct()
    {
        $this->stockChanges = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): static
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @return Collection<int, StockChange>
     */
    public function getStockChanges(): Collection
    {
        return $this->stockChanges;
    }

    public function addStockChange(StockChange $stockChange): static
    {
        if (!$this->stockChanges->contains($stockChange)) {
            $this->stockChanges->add($stockChange);
            $stockChange->setItem($this);
        }

        return $this;
    }

    public function removeStockChange(StockChange $stockChange): static
    {
        if ($this->stockChanges->removeElement($stockChange)) {
            // set the owning side to null (unless already changed)
            if ($stockChange->getItem() === $this) {
                $stockChange->setItem(null);
            }
        }

        return $this;
    }

    public function getLocation(): ?Location
    {
        return $this->location;
    }

    public function setLocation(Location $location): static
    {
        $this->location = $location;

        return $this;
    }
}
