<?php

namespace App\DTO\ResponseEntity\User;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;

class ResponseOneUser
{
    private int $id;
    private string $name;
    private string $mail;

    #[OA\Property(
        type: 'array',
        items: new OA\Items(ref: new Model(type: ResponseAllUser::class))
    )]
    private Collection $friends;

    function __construct($user) {
        $this->setId($user->getId());
        $this->setName($user->getName());
        $this->setMail($user->getMail());

        $this->friends = new ArrayCollection();
        foreach ($user->getFriends() as $friend)
            $this->friends[] = new ResponseUserMinimized($friend);
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