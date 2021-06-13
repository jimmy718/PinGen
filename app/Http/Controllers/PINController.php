<?php

namespace App\Http\Controllers;

use App\Events\PINsUsed;
use App\Jobs\RefreshPINsIfRequired;
use App\PIN;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class PINController extends Controller
{
    /**
     * @var int
     */
    private const DEFAULT_COUNT = 1;

    /**
     * @param Request $request
     * @return Collection
     */
    public function index(Request $request): Collection
    {
        RefreshPINsIfRequired::dispatch(
            $request->input('count', self::DEFAULT_COUNT)
        );

        $pins = PIN::query()
            ->where('used', false)
            ->inRandomOrder()
            ->limit($request->input('count', self::DEFAULT_COUNT))
            ->get();

        PINsUsed::dispatch($pins);

        return $pins;
    }
}
