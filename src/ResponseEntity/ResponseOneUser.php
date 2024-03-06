<?php

namespace App\ResponseEntity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class ResponseOneUser
{
    private int $id;
    private string $name;
    private string $mail;
    private Collection $friends;

    function __construct($user) {
        $this->setId($user->getId());
        $this->setName($user->getName());
        $this->setMail($user->getMail());

        $this->friends = new ArrayCollection();
        foreach ($user->getFriends() as $friend)
            $this->friends[] = new ResponseUserFriend($friend);
    }
    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getMail(): string
    {
        return $this->mail;
    }

    public function setMail(string $mail): void
    {
        $this->mail = $mail;
    }

    public function getFriends(): Collection
    {
        return $this->friends;
    }

    public function setFriends(Collection $friends): void
    {
        $this->friends = $friends;
    }
}