<?php

namespace App\Entity;

use App\Repository\ItemRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ItemRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Item
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $summary;

    /**
     * @ORM\Column(type="text")
     */
    private $fluff;

    /**
     * @ORM\Column(type="text")
     */
    private $crunch;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="items")
     * @ORM\JoinColumn(nullable=false)
     */
    private $creator;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $img;

    /**
     * @ORM\ManyToMany(targetEntity=Npc::class, mappedBy="Item")
     */
    private $npcs;

    /**
     * @ORM\ManyToMany(targetEntity=Location::class, mappedBy="Item")
     */
    private $locations;

    /**
     * @ORM\ManyToMany(targetEntity=Intrigue::class, inversedBy="items")
     */
    private $Intrigue;

    /**
     * @ORM\ManyToMany(targetEntity=Encounter::class, inversedBy="items")
     */
    private $Encounter;

    /**
     * @ORM\ManyToMany(targetEntity=Item::class, inversedBy="items")
     */
    private $item;

    /**
     * @ORM\ManyToMany(targetEntity=Item::class, mappedBy="item")
     */
    private $items;

    public function __construct()
    {
        $this->npcs = new ArrayCollection();
        $this->locations = new ArrayCollection();
        $this->Intrigue = new ArrayCollection();
        $this->Encounter = new ArrayCollection();
        $this->item = new ArrayCollection();
        $this->items = new ArrayCollection();
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

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function setSummary(string $summary): self
    {
        $this->summary = $summary;

        return $this;
    }

    public function getFluff(): ?string
    {
        return $this->fluff;
    }

    public function setFluff(string $fluff): self
    {
        $this->fluff = $fluff;

        return $this;
    }

    public function getCrunch(): ?string
    {
        return $this->crunch;
    }

    public function setCrunch(string $crunch): self
    {
        $this->crunch = $crunch;

        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    public function setCreatedAtValue(): void
    {
        $this->createdAt = new \DateTimeImmutable('now');
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @ORM\PreUpdate
     * @ORM\PrePersist
     */
    public function setUpdatedAtValue(): void
    {
        $this->updatedAt = new \DateTimeImmutable('now');
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getCreator(): ?User
    {
        return $this->creator;
    }

    public function setCreator(?User $creator): self
    {
        $this->creator = $creator;

        return $this;
    }

    public function getImg(): ?string
    {
        return $this->img;
    }

    public function setImg(string $img): self
    {
        $this->img = $img;

        return $this;
    }

    public function getPath(): string
    {
        return("item");
    }

    public function getTypeName(): string
    {
        return("Objet");
    }

    /**
     * @return Collection<int, Npc>
     */
    public function getNpcs(): Collection
    {
        return $this->npcs;
    }

    public function addNpc(Npc $npc): self
    {
        if (!$this->npcs->contains($npc)) {
            $this->npcs[] = $npc;
            $npc->addItem($this);
        }

        return $this;
    }

    public function removeNpc(Npc $npc): self
    {
        if ($this->npcs->removeElement($npc)) {
            $npc->removeItem($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Location>
     */
    public function getLocations(): Collection
    {
        return $this->locations;
    }

    public function addLocation(Location $location): self
    {
        if (!$this->locations->contains($location)) {
            $this->locations[] = $location;
            $location->addItem($this);
        }

        return $this;
    }

    public function removeLocation(Location $location): self
    {
        if ($this->locations->removeElement($location)) {
            $location->removeItem($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Intrigue>
     */
    public function getIntrigue(): Collection
    {
        return $this->Intrigue;
    }

    public function addIntrigue(Intrigue $intrigue): self
    {
        if (!$this->Intrigue->contains($intrigue)) {
            $this->Intrigue[] = $intrigue;
        }

        return $this;
    }

    public function removeIntrigue(Intrigue $intrigue): self
    {
        $this->Intrigue->removeElement($intrigue);

        return $this;
    }

    /**
     * @return Collection<int, Encounter>
     */
    public function getEncounter(): Collection
    {
        return $this->Encounter;
    }

    public function addEncounter(Encounter $encounter): self
    {
        if (!$this->Encounter->contains($encounter)) {
            $this->Encounter[] = $encounter;
        }

        return $this;
    }

    public function removeEncounter(Encounter $encounter): self
    {
        $this->Encounter->removeElement($encounter);

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getItem(): Collection
    {
        return $this->item;
    }

    public function addItem(self $item): self
    {
        if (!$this->item->contains($item)) {
            $this->item[] = $item;
        }

        return $this;
    }

    public function removeItem(self $item): self
    {
        $this->item->removeElement($item);

        return $this;
    }

    public function __toString(): string
    {
        return $this->name;
    }

    /**
     * @return Collection<int, self>
     */
    public function getItems(): Collection
    {
        return $this->items;
    }
}
