<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * @property string value
 * @property boolean used
 *
 * @package App
 */
class PIN extends Model
{
    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @return Collection
     */
    public function digits(): Collection
    {
        return collect(str_split($this->value))->map(function ($digit) {
            return intval($digit);
        });
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return $this->hasUniqueDigits() && $this->hasFewConsecutiveDigits();
    }

    /**
     * @return bool
     */
    protected function hasFewConsecutiveDigits(): bool
    {
        $count = 0;
        $nextDigit = 0;

        foreach ($this->digits() as $digit) {
            if ($nextDigit > 0 && $digit === $nextDigit) {
                $count++;
            } else {
                $count = 0;
            }
            if ($count == 2) {
                return false;
            }
            $nextDigit = $digit + 1;
        }
        return true;
    }

    /**
     * @return bool
     */
    protected function hasUniqueDigits(): bool
    {
        return $this->digits()->unique()->count() === 4;
    }
}
