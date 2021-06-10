<?php

namespace Tests\Unit;

use App\PIN;
use PHPUnit\Framework\TestCase;

class PINTest extends TestCase
{
    /** @test */
    public function it_should_not_contain_repeated_digits()
    {
        $pin = new PIN(['value' => '1112']);
        $this->assertFalse($pin->isValid());

        $pin = new PIN(['value' => '1122']);
        $this->assertFalse($pin->isValid());

        $pin = new PIN(['value' => '1233']);
        $this->assertFalse($pin->isValid());

        $pin = new PIN(['value' => '1324']);
        $this->assertTrue($pin->isValid());
    }

    
}
