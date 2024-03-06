<?php

namespace App\ResponseEntity;

class ResponseAllUser
{
    private int $id;
    private string $name;
    private string $mail;

    function __construct($user) {
        $this->setId($user->getId());
        $this->setName($user->getName());
        $this->setMail($user->getMail());
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
}