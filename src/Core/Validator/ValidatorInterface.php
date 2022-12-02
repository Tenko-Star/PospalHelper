<?php

namespace PospalHelper\Core\Validator;

interface ValidatorInterface
{
    /**
     * @param string $name
     * @param array $data
     * @return bool
     */
    public function validate(string $name, array $data, array $args): bool;

    /**
     * @return string
     */
    public function message(string $name): string;
}