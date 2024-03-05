<?php

namespace App\Entity;

use App\Repository\UserRepository;
use App\RequestEntity\RequestAddUser;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
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

    #[ORM\Column(length: 255)]
    private ?string $login = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'friends')]
    private ?self $friendProperty = null;

    #[ORM\OneToMany(targetEntity: self::class, mappedBy: 'friendProperty')]
    private Collection $friends;

    public function __construct()
    {
        $this->friends = new ArrayCollection();
    }

    public function extractData(RequestAddUser $requestAddUser)
    {
        $this->name = $requestAddUser->getName();
        $this->mail = $requestAddUser->getMail();
        $this->login = $requestAddUser->getLogin();
        $this->password = $requestAddUser->getPassword();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(?string $mail): static
    {
        $this->mail = $mail;

        return $this;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): static
    {
        $this->login = $login;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function setId(string $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getFriendProperty(): ?self
    {
        return $this->friendProperty;
    }

    public function setFriendProperty(?self $friendProperty): static
    {
        $this->friendProperty = $friendProperty;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getFriends(): Collection
    {
        return $this->friends;
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
