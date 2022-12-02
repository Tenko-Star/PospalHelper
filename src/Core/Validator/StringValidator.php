<?php

namespace PospalHelper\Core\Validator;

class StringValidator implements ValidatorInterface
{

    use HandleArgumentTrait, MessageTrait;

    /**
     * @inheritDoc
     */
    public function validate(string $name, array $data, array $args): bool
    {
        $this->message = '';

        if (!is_string($data[$name])) {
            $this->message = $name . ' is not a string';
            return false;
        }

        return $this->handleArgs($args, function ($func, $list) use ($name, $data) {
            switch ($func) {
                case 'min':
                    if (!isset($list[0]) || !is_numeric($list[0])) {
                        $this->message = 'Not a correct param';
                        return false;
                    }

                    if (strlen($data[$name]) < $list[0]) {
                        $this->message = "The string length must be less than $list[0]";
                        return false;
                    }
                    break;
                case 'max':
                    if (!isset($list[0]) || !is_numeric($list[0])) {
                        $this->message = 'Not a correct param';
                        return false;
                    }

                    if (strlen($data[$name]) > $list[0]) {
                        $this->message = "The string length must be less than $list[0]";
                        return false;
                    }
                    break;
                case 'range':
                    if (!isset($list[0]) || !isset($list[1]) || !is_numeric($list[0]) || !is_numeric($list[1])) {
                        $this->message = 'Not a correct param';
                        return false;
                    }

                    $min = (int)$list[0];
                    $max = (int)$list[1];
                    if ($min > $max) {
                        $min = $min ^ $max;
                        $max = $min ^ $max;
                        $min = $min ^ $max;
                    }

                    $len = strlen($data[$name]);
                    if ($len < $min) {
                        $this->message = "The string must be between $list[0] and $list[1] characters in length";
                        return false;
                    }

                    if ($len > $max) {
                        $this->message = "The string must be between $list[0] and $list[1] characters in length";
                        return false;
                    }
                    break;
                default:
                    break;
            }

            return true;
        });
    }
}