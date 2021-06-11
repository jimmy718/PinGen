<?php

use App\PIN;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i <= 9999; $i++) {
            $pin = new PIN([
                'value' => str_pad($i, 4, '0', STR_PAD_LEFT)
            ]);

            if ($pin->isValid()) {
                $pin->save();
            }
        }
    }
}
