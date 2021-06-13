<?php

namespace App\Http\Controllers;

use App\PIN;
use Illuminate\Http\Request;

class PINController extends Controller
{
    public function index(Request $request)
    {
        if ($request->input('count', 5) > PIN::where('used', false)->count()) {
            PIN::where('used', true)->update(['used' => false]);
        }

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
