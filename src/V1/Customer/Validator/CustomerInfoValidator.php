<?php

namespace PospalHelper\V1\Customer\Validator;

use PospalHelper\Core\BaseValidator;

class CustomerInfoValidator extends BaseValidator
{
    protected array $rules = [
        'customerUid' => ['require', 'string'],
        'name' => ['string'],
        'phone' => ['string'],
        'birthday' => ['string'],
        'qq' => ['string'],
        'email' => ['string'],
        'address' => ['string'],
        'remarks' => ['string'],
        'enable' => ['integer|in,-1,0,1'],
        'categoryName' => ['string'],
        'discount' => ['decimal'],
        'point' => ['decimal'],
        'balance' => ['decimal'],
        'onAccount' => ['integer|in,0,1'],
        'expiryDate' => ['string'],
        'extInfo' => ['array']
    ];
}