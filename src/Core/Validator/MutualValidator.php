<?php

namespace PospalHelper\Core\Validator;

class MutualValidator implements ValidatorInterface
{
    use MessageTrait, HandleArgumentTrait;

    /**
     * @inheritDoc
     * @return array|bool
     */
    public function validate(string $name, array $data, array $args): bool
    {
        $this->message = '';

        return $this->handleArgs($args, function ($func, $list) use ($name, $data) {
            if ($func != 'name') {
                return false;
            }

            $contrast = $list[0] ?? null;
            if (!$contrast) {
                $this->message = 'Lack of reference';
                return false;
            }

            $a = isset($data[$name]);
            $b = isset($data[$contrast]);

            if ($a ^ $b) {
                return true;
            }

            $this->message = "$name and $contrast are not mutually exclusive";
            return false;
        });
    }
}