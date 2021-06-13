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
     * @return void
     */
    public static function resetUsed(): void
    {
        static::where('used', true)->update(['used' => false]);
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
            if ($count === 3) return false;

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
        return $this->digits()->unique()->count() === 4;
    }
}
