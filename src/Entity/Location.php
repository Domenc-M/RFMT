<?php

namespace App\Entity;

use App\Repository\LocationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LocationRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Location
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
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="locations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $creator;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $img;

    /**
     * @ORM\ManyToMany(targetEntity=Npc::class, mappedBy="Location")
     */
    private $npcs;

    /**
     * @ORM\ManyToMany(targetEntity=Item::class, inversedBy="locations")
     */
    private $Item;

    /**
     * @ORM\ManyToMany(targetEntity=Intrigue::class, inversedBy="locations")
     */
    private $Intrigue;

    /**
     * @ORM\ManyToMany(targetEntity=Encounter::class, inversedBy="locations")
     */
    private $Encounter;

    /**
     * @ORM\ManyToMany(targetEntity=Location::class, inversedBy="locations")
     */
    private $location;

    /**
     * @ORM\ManyToMany(targetEntity=Location::class, mappedBy="location")
     */
    private $locations;

    public function __construct()
    {
        $this->npcs = new ArrayCollection();
        $this->Item = new ArrayCollection();
        $this->Intrigue = new ArrayCollection();
        $this->Encounter = new ArrayCollection();
        $this->location = new ArrayCollection();
        $this->locations = new ArrayCollection();
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
        return("location");
    }

    public function getTypeName(): string
    {
        return("Lieu");
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
            $npc->addLocation($this);
        }

        return $this;
    }

    public function removeNpc(Npc $npc): self
    {
        if ($this->npcs->removeElement($npc)) {
            $npc->removeLocation($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Item>
     */
    public function getItem(): Collection
    {
        return $this->Item;
    }

    public function addItem(Item $item): self
    {
        if (!$this->Item->contains($item)) {
            $this->Item[] = $item;
        }

        return $this;
    }

    public function removeItem(Item $item): self
    {
        $this->Item->removeElement($item);

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
    public function getLocation(): Collection
    {
        return $this->location;
    }

    public function addLocation(self $location): self
    {
        if (!$this->location->contains($location)) {
            $this->location[] = $location;
        }

        return $this;
    }

    public function removeLocation(self $location): self
    {
        $this->location->removeElement($location);

        return $this;
    }
    
    public function __toString(): string
    {
        return $this->name;
    }

    /**
     * @return Collection<int, self>
     */
    public function getLocations(): Collection
    {
        return $this->locations;
    }
}
