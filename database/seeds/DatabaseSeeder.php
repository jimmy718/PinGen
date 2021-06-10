<?php

use App\PIN;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1000; $i <= 9999; $i++) {
            $pin = new PIN(['value' => $i]);
            if ($pin->isValid()) {
                $pin->save();
            }
        }
    }
}
