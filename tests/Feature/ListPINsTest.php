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
        $returnedPINs->pluck('id')->each(function ($id) {
            $this->assertDatabaseHas('PINs', [
                'id' => $id,
                'used' => true
            ]);
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

        $this->assertTrue($countOfConsecutiveIds <= 2);
    }

    /** @test */
    public function when_all_PINs_are_exhausted_all_used_markers_are_reset()
    {
        PIN::create(['value' => '1356']);
        PIN::create(['value' => '1357']);
        PIN::create(['value' => '1358']);

        $this->getJson(route('PINs.index', ['count' => 3]))->assertJsonCount(3);

        // We should be able to request the same PINs again as all 3 are exhausted
        $this->getJson(route('PINs.index', ['count' => 3]))->assertJsonCount(3);
    }

    /** @test */
    public function PINs_marked_as_used_should_not_be_returned()
    {
        $unused = PIN::create(['value' => '1356', 'used' => false]);
        PIN::create(['value' => '1357', 'used' => true]);
        PIN::create(['value' => '1358', 'used' => true]);

        $response = $this->getJson(route('PINs.index', ['count' => 1]))->assertJsonCount(1);

        $this->assertTrue($unused->is($response->original->first()));
    }

    /** @test */
    public function when_more_PINs_are_requested_than_are_available_the_flags_will_be_reset_and_then_returned()
    {
        PIN::create(['value' => '1356', 'used' => false]);
        PIN::create(['value' => '1357', 'used' => true]);
        PIN::create(['value' => '1358', 'used' => true]);
        PIN::create(['value' => '1359', 'used' => true]);

        $response = $this->getJson(route('PINs.index', ['count' => 2]))->assertJsonCount(2);
        $returnedPINs = $response->original;

        // should have 2 unused PINs that do not match ids with returned PINs
        $this->assertCount(2, PIN::where('used', false)->whereNotIn('id', $returnedPINs->pluck('id'))->get());

        // should have 2 used PINs which match ids with the 2 returned
        $usedPINs = PIN::where('used', true)->get();

        $this->assertCount(2, $usedPINs);
        $this->assertEqualsCanonicalizing($returnedPINs->pluck('id'), $usedPINs->pluck('id'));
    }

    /** @test */
    public function no_more_than_10_PINs_can_be_requested_at_once()
    {
        $this->getJson(route('PINs.index', ['count' => 1]))->assertOk();
        $this->getJson(route('PINs.index', ['count' => 10]))->assertOk();

        $this->getJson(route('PINs.index', ['count' => 11]))->assertJsonValidationErrors([
            'count'
        ]);
        $this->getJson(route('PINs.index', ['count' => 100]))->assertJsonValidationErrors([
            'count'
        ]);
    }
}
