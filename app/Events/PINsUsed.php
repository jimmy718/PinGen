<?php

namespace App\Events;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Events\Dispatchable;

class PINsUsed
{
    use Dispatchable;

    /**
     * @var Collection
     */
    public $pins;

    /**
     * @return void
     */
    public function __construct(Collection $pins)
    {
        $this->pins = $pins;
    }
}
