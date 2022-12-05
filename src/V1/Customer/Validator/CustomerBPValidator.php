<?php

namespace PospalHelper\V1\Customer\Validator;

use PospalHelper\Core\BaseValidator;

class CustomerBPValidator extends BaseValidator
{
    protected array $rules = [
        'customerUid' => ['require', 'string'],
        'balanceIncrement' => ['mutual|name,balanceIncrement', 'decimal'],
        'pointIncrement' => ['mutual|name,balanceIncrement', 'decimal']
    ];
}