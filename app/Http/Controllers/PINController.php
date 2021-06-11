<?php

namespace App\Http\Controllers;

use App\PIN;
use Illuminate\Http\Request;

class PINController extends Controller
{
    public function index(Request $request)
    {
        $pins = PIN::query()
            ->limit($request->input('count', 5))
            ->get();

        $pins->each(function (PIN $pin) {
            $pin->markUsed();
        });

        return $pins;
    }
}
