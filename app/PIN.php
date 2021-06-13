<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * @property string value
 * @property boolean used
 *
 * @mixin Builder
 *
 * @package App
 */
class PIN extends Model
{
    /**
     * @var int
     */
    private const UNIQUE_DIGITS = 4;

    /**
     * @var int
     */
    private const MAXIMUM_CONSECUTIVE_DIGITS = 2;

    /**
     * @var string
     */
    protected $table = 'PINs';

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
    public function markUsed(): bool
    {
        return $this->update(['used' => true]);
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
        $count = 1;
        $nextConsecutiveDigit = -1;

        foreach ($this->digits() as $digit) {
            if ($count === self::MAXIMUM_CONSECUTIVE_DIGITS + 1) {
                return false;
            }

            $digit === $nextConsecutiveDigit
                ? $count ++
                : $count = 1;

            $nextConsecutiveDigit = $digit + 1;
        }

        return true;
    }

    /**
     * @return bool
     */
    protected function hasUniqueDigits(): bool
    {
        return $this->digits()->unique()->count() === self::UNIQUE_DIGITS;
    }
}
