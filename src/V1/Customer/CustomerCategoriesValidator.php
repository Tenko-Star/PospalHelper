<?php

namespace PospalHelper\V1\Customer;

use PospalHelper\Core\BaseValidator;

class CustomerCategoriesValidator extends BaseValidator
{
    protected array $rules = [
        'customerUid' => ['mutual|name,customerNumber', 'string'],
        'customerNumber' => ['mutual|name,customerUid', 'string'],
        'discount' => ['require', 'decimal|range,0,1.0']
    ];
}