<?php

use Database\Seeders\CountriesTableSeeder;
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
         $this->call([
             AdminTableSeeder::class,
             BranchTableSeeder::class,
             CountriesTableSeeder::class
         ]);

    }
}
