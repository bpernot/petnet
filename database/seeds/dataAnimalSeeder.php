<?php

use Illuminate\Database\Seeder;

class dataAnimalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory('App\Animal', 10)->create();
    }
}
