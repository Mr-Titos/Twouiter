<?php

namespace App\DTO\RequestEntity\User;

use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RequestAddUser
{
    #[NotBlank()]
    private ?string $name = null;

    #[NotBlank()]
    #[Email]
    private ?string $mail = null;

    #[Length(min: 0, max: 511)]
    private ?string $description = null;

    #[NotBlank()]
    #[Length(min: 3, max: 180)]
    private ?string $login = null;

    #[NotBlank()]
    #[Length(min: 8, max: 255)]
    private ?string $password = null;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
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
}