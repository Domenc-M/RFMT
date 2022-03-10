<?php

namespace App\Entity;

use App\Repository\NpcRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=NpcRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Npc
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
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="Npcs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $creator;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $img;

    /**
     * @ORM\ManyToMany(targetEntity=Location::class, inversedBy="npcs")
     */
    private $Location;

    /**
     * @ORM\ManyToMany(targetEntity=Item::class, inversedBy="npcs")
     */
    private $Item;

    /**
     * @ORM\ManyToMany(targetEntity=Intrigue::class, inversedBy="npcs")
     */
    private $Intrigue;

    /**
     * @ORM\ManyToMany(targetEntity=Encounter::class, inversedBy="npcs")
     */
    private $Encounter;

    /**
     * @ORM\ManyToMany(targetEntity=Npc::class, inversedBy="npcs")
     */
    private $npc;

    /**
     * @ORM\ManyToMany(targetEntity=Npc::class, mappedBy="npc")
     */
    private $npcs;

    public function __construct()
    {
        $this->Location = new ArrayCollection();
        $this->Item = new ArrayCollection();
        $this->Intrigue = new ArrayCollection();
        $this->Encounter = new ArrayCollection();
        $this->npc = new ArrayCollection();
        $this->npcs = new ArrayCollection();
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
     * @ORM\PrePersist
     */
    public function setCreatedAtValue(): void
    {
        $this->createdAt = new \DateTimeImmutable('now');
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

    /**
     * @ORM\PreUpdate
     * @ORM\PrePersist
     */
    public function setUpdatedAtValue(): void
    {
        $this->updatedAt = new \DateTimeImmutable('now');
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
        return("npc");
    }

    public function getTypeName(): string
    {
        return("PNJ");
    }

    /**
     * @return Collection<int, Location>
     */
    public function getLocation(): Collection
    {
        return $this->Location;
    }

    public function addLocation(Location $location): self
    {
        if (!$this->Location->contains($location)) {
            $this->Location[] = $location;
        }

        return $this;
    }

    public function removeLocation(Location $location): self
    {
        $this->Location->removeElement($location);

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
    public function getNpc(): Collection
    {
        return $this->npc;
    }

    public function addNpc(self $npc): self
    {
        if (!$this->npc->contains($npc)) {
            $this->npc[] = $npc;
        }

        return $this;
    }

    public function removeNpc(self $npc): self
    {
        $this->npc->removeElement($npc);

        return $this;
    }

    public function __toString(): string
    {
        return $this->name;
    }

    /**
     * @return Collection<int, self>
     */
    public function getNpcs(): Collection
    {
        return $this->npcs;
    }
}
