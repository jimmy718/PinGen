<?php

namespace App\Builders;

use Illuminate\Database\Eloquent\Builder;

class PINQueryBuilder extends Builder
{
    /**
     * PINQueryBuilder constructor.
     */
    public function __construct($query)
    {
        parent::__construct($query);
    }

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
