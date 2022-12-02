<?php

namespace PospalHelper\Core\Validator;

trait MessageTrait
{
    private string $message = '';

    /**
     * @inheritDoc
     */
    public function message(string $name): string
    {
        return $this->message;
    }
}