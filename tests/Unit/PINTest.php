<?php

namespace Tests\Unit;

use App\PIN;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PINTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_not_contain_repeated_digits()
    {
        // 4 same
        $pin = new PIN(['value' => '0000']);
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
        $pin = new PIN(['value' => '1245']);
        $this->assertTrue($pin->isValid());

        // no sequence
        $pin = new PIN(['value' => '1524']);
        $this->assertTrue($pin->isValid());
    }

    /** @test */
    public function it_can_be_marked_as_used()
    {
        $pin = PIN::create([
            'value' => '1468',
            'used' => false
        ]);

        $pin->markUsed();

        $this->assertTrue($pin->used);
    }
}
