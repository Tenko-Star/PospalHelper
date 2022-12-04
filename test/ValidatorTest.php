<?php

use PHPUnit\Framework\TestCase;

class ValidatorTest extends TestCase
{
    public function testValidator()
    {
        $empty = new class extends \PospalHelper\Core\BaseValidator{};

        $empty->add('di', ['decimal|range,0.5,101.']);

        $data = [
            'di' => '101.1'
        ];

        $result = $empty->data($data)->check();

        $this->assertFalse($result);
    }
}