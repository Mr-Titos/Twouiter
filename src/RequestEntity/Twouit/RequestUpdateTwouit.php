<?php

namespace App\RequestEntity\Twouit;

use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

class RequestUpdateTwouit
{
    #[NotBlank()]
    private ?string $title = null;

    #[NotBlank()]
    #[Length(min: 1, max: 511)]
    private ?string $msgContent = null;

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    public function getMsgContent(): ?string
    {
        return $this->msgContent;
    }

    public function setMsgContent(?string $msgContent): void
    {
        $this->msgContent = $msgContent;
    }
}