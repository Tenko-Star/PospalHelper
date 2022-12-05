<?php

namespace PospalHelper\V1\Customer\Validator;

use PospalHelper\Core\BaseValidator;

class CustomerPasswordValidator extends BaseValidator
{
    protected array $rules = [
        'customerUid' => ['require', 'string'],
        'customerPassword' => ['require', 'string|range,1,16']
    ];
}