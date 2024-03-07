<?php

namespace App\Controller\AbstractTwouiterController;

class TwouiterControllerResponse
{
    private int $statusCode;
    private ?string $message;
    private object|array|null $content;

    function __construct($statusCode, $message, $content) {
        $this->setStatusCode($statusCode);
        $this->setMessage($message);
        $this->setContent($content);
    }
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function setStatusCode(int $statusCode): void
    {
        $this->statusCode = $statusCode;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): void
    {
        $this->message = $message;
    }

    public function getContent() : object|array|null
    {
        return $this->content;
    }

    public function setContent(object|array|null $content): void
    {
        $this->content = $content;
    }
}