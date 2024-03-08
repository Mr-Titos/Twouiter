<?php

namespace App\DTO\ResponseEntity\User;

class ResponseUserMinimized
{
    private int $id;
    private string $name;

    function __construct($user) {
        $this->setId($user->getId());
        $this->setName($user->getName());
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
}