<?php

namespace PospalHelper\Core\Validator;

class ArrayValidator implements ValidatorInterface
{

    use HandleArgumentTrait, MessageTrait;

    /**
     * @inheritDoc
     */
    public function validate(string $name, array $data, array $args): bool
    {
        $this->message = '';

        if (!is_array($data[$name])) {
            $this->message = $name . ' is not an array';
            return false;
        }

        return $this->handleArgs($args, function ($func, $list) use ($name, $data) {
            switch ($func) {
                case 'min':
                    if (!isset($list[0]) || !is_numeric($list[0])) {
                        $this->message = 'Not a correct param';
                        return false;
                    }

                    if (count($data[$name]) < $list[0]) {
                        $this->message = "The array must contain more than $list[0] contents";
                        return false;
                    }
                    break;
                case 'max':
                    if (!isset($list[0]) || !is_numeric($list[0])) {
                        $this->message = 'Not a correct param';
                        return false;
                    }

                    if (count($data[$name]) > $list[0]) {
                        $this->message = "The array contains a maximum of $list[0] contents";
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

                    if (count($data[$name]) < $min) {
                        $this->message = "The array contents must be between $list[0] and $list[1]";
                        return false;
                    }

                    if (count($data[$name]) > $max) {
                        $this->message = "The array contents must be between $list[0] and $list[1]";
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