<?php

namespace App\Listeners;

use App\Events\PINsUsed;
use App\PIN;

class MarkPINsAsUsed
{
    /**
     * @param PINsUsed $event
     * @return void
     */
    public function handle(PINsUsed $event)
    {
        $event->pins->each(function (PIN $pin) {
            $pin->markUsed();
        });
    }
}
