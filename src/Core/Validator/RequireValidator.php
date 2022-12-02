<?php

namespace PospalHelper\Core\Validator;

class RequireValidator implements ValidatorInterface
{

    /**
     * @inheritDoc
     */
    public function validate(string $name, array $data, array $args): bool
    {
        return isset($data[$name]);
    }

    /**
     * @inheritDoc
     */
    public function message(string $name = ''): string
    {
        return $name . 'could not be empty';
    }
}