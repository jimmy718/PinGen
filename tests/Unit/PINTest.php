<?php

namespace Tests\Unit;

use App\PIN;
use PHPUnit\Framework\TestCase;

class PINTest extends TestCase
{
    /** @test */
    public function it_should_not_contain_repeated_digits()
    {
        // 4 same
        $pin = new PIN(['value' => '1111']);
        $this->assertFalse($pin->isValid());

        // 2 same
        $pin = new PIN(['value' => '1122']);
        $this->assertFalse($pin->isValid());

        // no repeated digits
        $pin = new PIN(['value' => '1324']);
        $this->assertTrue($pin->isValid());
    }

    /** @test */
    public function it_should_not_contain_sequences_of_more_than_two_consecutive_digits()
    {
        // 4 digit sequence
        $pin = new PIN(['value' => '1234']);
        $this->assertFalse($pin->isValid());

        // 2 digit sequence
        $pin = new PIN(['value' => '1254']);
        $this->assertTrue($pin->isValid());

        // no sequence
        $pin = new PIN(['value' => '1524']);
        $this->assertTrue($pin->isValid());
    }
}
