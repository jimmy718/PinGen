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

        $returnedPINs->map->used->each(function ($flag) {
            $this->assertTrue($flag);
        });

        $this->assertCount(1, PIN::where('used', false)->get());
    }
}
