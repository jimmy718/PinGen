<?php

namespace App\Http\Controllers;

use App\Jobs\RefreshPINsIfRequired;
use App\PIN;
use Illuminate\Http\Request;

class PINController extends Controller
{
    public function index(Request $request)
    {
        RefreshPINsIfRequired::dispatch($request->input('count', 5));

        $pins = PIN::query()
            ->where('used', false)
            ->inRandomOrder()
            ->limit($request->input('count', 5))
            ->get();

        $pins->each(function (PIN $pin) {
            $pin->markUsed();
        });

        if (PIN::query()->where('used', false)->count() === 0) {
            PIN::where('used', true)->update(['used' => false]);
        }

        return $pins;
    }
}
