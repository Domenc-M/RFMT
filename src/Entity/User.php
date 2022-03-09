<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"username"}, message="There is already an account with this username")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\OneToMany(targetEntity=Npc::class, mappedBy="creator", orphanRemoval=true)
     */
    private $Npcs;

    /**
     * @ORM\OneToMany(targetEntity=Location::class, mappedBy="creator", orphanRemoval=true)
     */
    private $locations;

    /**
     * @ORM\OneToMany(targetEntity=Item::class, mappedBy="creator", orphanRemoval=true)
     */
    private $items;

    /**
     * @ORM\OneToMany(targetEntity=Encounter::class, mappedBy="creator", orphanRemoval=true)
     */
    private $encounters;

    /**
     * @ORM\OneToMany(targetEntity=Intrigue::class, mappedBy="creator", orphanRemoval=true)
     */
    private $intrigues;

    /**
     * @ORM\OneToMany(targetEntity=Hook::class, mappedBy="creator")
     */
    private $hooks;

    /**
     * @ORM\OneToMany(targetEntity=Table::class, mappedBy="creator", orphanRemoval=true)
     */
    private $tables;

    public function __construct()
    {
        $this->Npcs = new ArrayCollection();
        $this->locations = new ArrayCollection();
        $this->items = new ArrayCollection();
        $this->encounters = new ArrayCollection();
        $this->intrigues = new ArrayCollection();
        $this->hooks = new ArrayCollection();
        $this->tables = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
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
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
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
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection<int, Npc>
     */
    public function getNpcs(): Collection
    {
        return $this->Npcs;
    }

    public function addNpc(Npc $Npc): self
    {
        if (!$this->Npcs->contains($Npc)) {
            $this->Npcs[] = $Npc;
            $Npc->setCreator($this);
        }

        return $this;
    }

    public function removeNpc(Npc $Npc): self
    {
        if ($this->Npcs->removeElement($Npc)) {
            // set the owning side to null (unless already changed)
            if ($Npc->getCreator() === $this) {
                $Npc->setCreator(null);
            }
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
            $location->setCreator($this);
        }

        return $this;
    }

    public function removeLocation(Location $location): self
    {
        if ($this->locations->removeElement($location)) {
            // set the owning side to null (unless already changed)
            if ($location->getCreator() === $this) {
                $location->setCreator(null);
            }
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
            $item->setCreator($this);
        }

        return $this;
    }

    public function removeItem(Item $item): self
    {
        if ($this->items->removeElement($item)) {
            // set the owning side to null (unless already changed)
            if ($item->getCreator() === $this) {
                $item->setCreator(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Encounter>
     */
    public function getEncounters(): Collection
    {
        return $this->encounters;
    }

    public function addEncounter(Encounter $encounter): self
    {
        if (!$this->encounters->contains($encounter)) {
            $this->encounters[] = $encounter;
            $encounter->setCreator($this);
        }

        return $this;
    }

    public function removeEncounter(Encounter $encounter): self
    {
        if ($this->encounters->removeElement($encounter)) {
            // set the owning side to null (unless already changed)
            if ($encounter->getCreator() === $this) {
                $encounter->setCreator(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Intrigue>
     */
    public function getIntrigues(): Collection
    {
        return $this->intrigues;
    }

    public function addIntrigue(Intrigue $intrigue): self
    {
        if (!$this->intrigues->contains($intrigue)) {
            $this->intrigues[] = $intrigue;
            $intrigue->setCreator($this);
        }

        return $this;
    }

    public function removeIntrigue(Intrigue $intrigue): self
    {
        if ($this->intrigues->removeElement($intrigue)) {
            // set the owning side to null (unless already changed)
            if ($intrigue->getCreator() === $this) {
                $intrigue->setCreator(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Hook>
     */
    public function getHooks(): Collection
    {
        return $this->hooks;
    }

    public function addHook(Hook $hook): self
    {
        if (!$this->hooks->contains($hook)) {
            $this->hooks[] = $hook;
            $hook->setCreator($this);
        }

        return $this;
    }

    public function removeHook(Hook $hook): self
    {
        if ($this->hooks->removeElement($hook)) {
            // set the owning side to null (unless already changed)
            if ($hook->getCreator() === $this) {
                $hook->setCreator(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Table>
     */
    public function getTables(): Collection
    {
        return $this->tables;
    }

    public function addTable(Table $table): self
    {
        if (!$this->tables->contains($table)) {
            $this->tables[] = $table;
            $table->setCreator($this);
        }

        return $this;
    }

    public function removeTable(Table $table): self
    {
        if ($this->tables->removeElement($table)) {
            // set the owning side to null (unless already changed)
            if ($table->getCreator() === $this) {
                $table->setCreator(null);
            }
        }

        return $this;
    }
}
