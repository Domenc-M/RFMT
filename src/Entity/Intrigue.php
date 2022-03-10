<?php

namespace App\Entity;

use App\Repository\IntrigueRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=IntrigueRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Intrigue
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
     * @ORM\Column(type="string", length=255)
     */
    private $type;

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
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="intrigues")
     * @ORM\JoinColumn(nullable=false)
     */
    private $creator;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $img;

    /**
     * @ORM\ManyToMany(targetEntity=Npc::class, mappedBy="Intrigue")
     */
    private $npcs;

    /**
     * @ORM\ManyToMany(targetEntity=Location::class, mappedBy="Intrigue")
     */
    private $locations;

    /**
     * @ORM\ManyToMany(targetEntity=Item::class, mappedBy="Intrigue")
     */
    private $items;

    /**
     * @ORM\ManyToMany(targetEntity=Encounter::class, inversedBy="intrigues")
     */
    private $Encounter;

    /**
     * @ORM\ManyToMany(targetEntity=Intrigue::class, inversedBy="intrigues")
     */
    private $intrigue;

    /**
     * @ORM\ManyToMany(targetEntity=Intrigue::class, mappedBy="intrigue")
     */
    private $intrigues;

    public function __construct()
    {
        $this->npcs = new ArrayCollection();
        $this->locations = new ArrayCollection();
        $this->items = new ArrayCollection();
        $this->Encounter = new ArrayCollection();
        $this->intrigue = new ArrayCollection();
        $this->intrigues = new ArrayCollection();
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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

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
        return("intrigue");
    }

    public function getTypeName(): string
    {
        return("Intrigue");
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
            $npc->addIntrigue($this);
        }

        return $this;
    }

    public function removeNpc(Npc $npc): self
    {
        if ($this->npcs->removeElement($npc)) {
            $npc->removeIntrigue($this);
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
            $location->addIntrigue($this);
        }

        return $this;
    }

    public function removeLocation(Location $location): self
    {
        if ($this->locations->removeElement($location)) {
            $location->removeIntrigue($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Item>
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(Item $item): self
    {
        if (!$this->items->contains($item)) {
            $this->items[] = $item;
            $item->addIntrigue($this);
        }

        return $this;
    }

    public function removeItem(Item $item): self
    {
        if ($this->items->removeElement($item)) {
            $item->removeIntrigue($this);
        }

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
    public function getIntrigue(): Collection
    {
        return $this->intrigue;
    }

    public function addIntrigue(self $intrigue): self
    {
        if (!$this->intrigue->contains($intrigue)) {
            $this->intrigue[] = $intrigue;
        }

        return $this;
    }

    public function removeIntrigue(self $intrigue): self
    {
        $this->intrigue->removeElement($intrigue);

        return $this;
    }

    public function __toString(): string
    {
        return $this->name;
    }

    /**
     * @return Collection<int, self>
     */
    public function getIntrigues(): Collection
    {
        return $this->intrigues;
    }
}
