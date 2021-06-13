<?php

namespace App\Builders;

use Illuminate\Database\Eloquent\Builder;

class PINBuilder extends Builder
{
    /**
     * @param int $amount
     * @return $this
     */
    public function randomUnused(int $amount): self
    {
        return $this->where('used', false)
            ->inRandomOrder()
            ->limit($amount);
    }
}
