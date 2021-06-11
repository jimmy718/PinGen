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
            try {
                // PIN::save overridden to reject any PIN from being
                // saved, whose "isValid" method returns false.
                PIN::create(['value' => str_pad($i, 4, '0', STR_PAD_LEFT)]);
            } catch (Exception $e) {
                continue;
            }
        }
    }
}
