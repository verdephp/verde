<?php

declare(strict_types=1);

namespace Verde;

final class Error
{
    /**
     * @var string
     */
    private $errorClass;

    /**
     * @var string
     */
    private $message;

    public function __construct(string $errorClass, string $message)
    {
        $this->errorClass = $errorClass;
        $this->message = $message;
    }

    public function getErrorClass(): string
    {
        return $this->errorClass;
    }

    public function getMessage(): string
    {
        return $this->message;
    }
}
