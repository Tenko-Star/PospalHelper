<?php

namespace PospalHelper\Core\Validator;

class DecimalValidator implements ValidatorInterface
{

    use HandleArgumentTrait, MessageTrait;

    /**
     * @inheritDoc
     */
    public function validate(string $name, array $data, array $args): bool
    {
        $this->message = '';

        if (!(is_numeric($data[$name]) && is_string($data[$name]))) {
            $this->message = $name . ' is not a decimal number';
            return false;
        }

        return $this->handleArgs($args, function ($func, $list) use ($name, $data) {
            switch ($func) {
                case 'min':
                    if (!isset($list[0]) || !is_numeric($list[0])) {
                        $this->message = 'Not a correct param';
                        return false;
                    }

                    if ($data[$name] < $list[0]) {
                        $this->message = "The variable cannot be less than $list[0]";
                        return false;
                    }
                    break;
                case 'max':
                    if (!isset($list[0]) || !is_numeric($list[0])) {
                        $this->message = 'Not a correct param';
                        return false;
                    }

                    if ($data[$name] > $list[0]) {
                        $this->message = "The variable cannot be greater than $list[0]";
                        return false;
                    }
                    break;
                case 'range':
                    if (!isset($list[0]) || !isset($list[1]) || !is_numeric($list[0]) || !is_numeric($list[1])) {
                        $this->message = 'Not a correct param';
                        return false;
                    }

                    $min = (float)$list[0];
                    $max = (float)$list[1];
                    if ($min > $max) {
                        $temp = $min;
                        $min = $max;
                        $max = $temp;
                    }

                    if ((float)$data[$name] < $min) {
                        $this->message = "The variable must be between $list[0] and $list[1]";
                        return false;
                    }

                    if ((float)$data[$name] > $max) {
                        $this->message = "The variable must be between $list[0] and $list[1]";
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