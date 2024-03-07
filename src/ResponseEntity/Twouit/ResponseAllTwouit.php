<?php

namespace App\ResponseEntity\Twouit;

class ResponseAllTwouit
{
    private int $id;
    private string $title;
    private string $msgContent;

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
}