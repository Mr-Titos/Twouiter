<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`userT`')]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $mail = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $login = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'friends')]
    private ?self $friendProperty = null;

    #[ORM\OneToMany(targetEntity: self::class, mappedBy: 'friendProperty')]
    private Collection $friends;

    #[ORM\Column(length: 511, nullable: true)]
    private ?string $description = null;

    #[ORM\OneToMany(targetEntity: Twouit::class, mappedBy: 'user', orphanRemoval: true)]
    private Collection $twouits;

    public function __construct()
    {
        $this->friends = new ArrayCollection();
        $this->twouits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(?string $mail): void
    {
        $this->mail = $mail;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(?string $login): void
    {
        $this->login = $login;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): void
    {
        $this->password = $password;
    }

    public function getFriendProperty(): ?User
    {
        return $this->friendProperty;
    }

    public function setFriendProperty(?User $friendProperty): void
    {
        $this->friendProperty = $friendProperty;
    }

    public function getFriends(): Collection
    {
        return $this->friends;
    }

    public function setFriends(Collection $friends): void
    {
        $this->friends = $friends;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return Collection<int, Twouit>
     */
    public function getTwouits(): Collection
    {
        return $this->twouits;
    }

    public function addTwouit(Twouit $twouit): static
    {
        if (!$this->twouits->contains($twouit)) {
            $this->twouits->add($twouit);
            $twouit->setUser($this);
        }

        return $this;
    }

    public function removeTwouit(Twouit $twouit): static
    {
        if ($this->twouits->removeElement($twouit)) {
            // set the owning side to null (unless already changed)
            if ($twouit->getUser() === $this) {
                $twouit->setUser(null);
            }
        }

        return $this;
    }

    public function addFriend(self $friend): static
    {
        if (!$this->friends->contains($friend)) {
            $this->friends->add($friend);
            $friend->setFriendProperty($this);
        }

        return $this;
    }

    public function removeFriend(self $friend): static
    {
        if ($this->friends->removeElement($friend)) {
            // set the owning side to null (unless already changed)
            if ($friend->getFriendProperty() === $this) {
                $friend->setFriendProperty(null);
            }
        }

        return $this;
    }
}
