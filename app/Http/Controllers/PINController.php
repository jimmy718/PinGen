<?php

namespace App\Http\Controllers;

use App\Events\PINsUsed;
use App\Http\Requests\PINListRequest;
use App\Jobs\RefreshPINsIfRequired;
use App\PIN;
use Illuminate\Database\Eloquent\Collection;

class PINController extends Controller
{
    /**
     * @var int
     */
    private const DEFAULT_COUNT = 1;

    /**
     * @param PINListRequest $request
     * @return Collection
     */
    public function index(PINListRequest $request): Collection
    {
        $count = $request->input('count', self::DEFAULT_COUNT);

        RefreshPINsIfRequired::dispatch($count);

        $pins = PIN::randomUnused($count)->get();

        PINsUsed::dispatch($pins);

        return $pins;
    }
}
