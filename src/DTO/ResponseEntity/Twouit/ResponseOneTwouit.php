<?php

namespace App\DTO\ResponseEntity\Twouit;

use App\DTO\ResponseEntity\User\ResponseUserMinimized;
use DateTime;

class ResponseOneTwouit
{
    private int $id;
    private string $title;
    private string $msgContent;
    private DateTime $entryDate;
    private ResponseUserMinimized $owner;

    function __construct($twouit) {
        $this->setId($twouit->getId());
        $this->setTitle($twouit->getTitle());
        $this->setMsgContent($twouit->getMsgContent());
        $this->setEntryDate($twouit->getEntryDate());
        $this->owner = new ResponseUserMinimized($twouit->getUser());
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getMsgContent(): string
    {
        return $this->msgContent;
    }

    public function setMsgContent(string $msgContent): void
    {
        $this->msgContent = $msgContent;
    }

    public function getEntryDate(): \DateTime
    {
        return $this->entryDate;
    }

    public function setEntryDate(\DateTime $entryDate): void
    {
        $this->entryDate = $entryDate;
    }

    public function getOwner(): ResponseUserMinimized
    {
        return $this->owner;
    }

    public function setOwner(ResponseUserMinimized $owner): void
    {
        $this->owner = $owner;
    }
}