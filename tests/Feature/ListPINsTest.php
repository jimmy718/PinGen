<?php

namespace Tests\Feature;

use App\PIN;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ListPINsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function the_requested_number_of_PINs_are_returned()
    {
        PIN::create(['value' => '1356']);
        PIN::create(['value' => '1357']);
        PIN::create(['value' => '1358']);
        PIN::create(['value' => '1359']);

        $this->getJson(route('PINs.index', ['count' => 3]))->assertJsonCount(3);
    }

    /** @test */
    public function returned_PINs_are_marked_as_used()
    {
        PIN::create(['value' => '1356']);
        PIN::create(['value' => '1357']);
        PIN::create(['value' => '1358']);
        PIN::create(['value' => '1359']);

        $response = $this->getJson(route('PINs.index', ['count' => 3]))->assertJsonCount(3);

        $returnedPINs = $response->original;
        $returnedPINs->pluck('used')->each(function ($flag) {
            $this->assertTrue($flag);
        });

        $this->assertCount(1, PIN::where('used', false)->get());
    }

    /** @test */
    public function PINs_are_returned_in_a_random_order()
    {
        PIN::create(['value' => '1356']);
        PIN::create(['value' => '1357']);
        PIN::create(['value' => '1358']);

        $response = $this->getJson(route('PINs.index', ['count' => 3]))->assertJsonCount(3);

        $returnedPINs = $response->original;
        $nextId = $returnedPINs->first()->id + 1;
        $countOfConsecutiveIds = 0;

        for ($i = 1; $i < $returnedPINs->count(); $i++) {
            if ($returnedPINs[$i]->id === $nextId) {
                $countOfConsecutiveIds++;
            }
        }

        $this->assertTrue(
            $countOfConsecutiveIds <= 2,
            'PINs where returned with more than 2 consecutive IDs'
        );
    }

    /** @test */
    public function when_all_PINs_are_exhausted_all_used_markers_are_reset()
    {
        PIN::create(['value' => '1356']);
        PIN::create(['value' => '1357']);
        PIN::create(['value' => '1358']);

        $this->getJson(route('PINs.index', ['count' => 3]))->assertJsonCount(3);

        // at this point all PINs have been used and "used" flags should have been reset
        $this->assertCount(3, PIN::where('used', false)->get());
    }
}
