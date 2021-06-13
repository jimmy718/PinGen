<?php

namespace App\Jobs;

use App\PIN;
use Illuminate\Foundation\Bus\Dispatchable;

class RefreshPINsIfRequired
{
    use Dispatchable;

    /**
     * @var int
     */
    private $requiredCount;

    /**
     * @return void
     */
    public function __construct(int $requiredCount)
    {
        $this->requiredCount = $requiredCount;
    }

    /**
     * @return void
     */
    public function handle()
    {
        if ($this->requiredCount > PIN::where('used', false)->count()) {
            PIN::where('used', true)->update(['used' => false]);
        }
    }
}
