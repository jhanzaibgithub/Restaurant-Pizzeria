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
         require_once __DIR__ . '/GroupTableSeeder.php';

         $this->call([
             AdminTableSeeder::class,
             BranchTableSeeder::class,
             GroupTableSeeder::class,
             CountriesTableSeeder::class
         ]);

    }
}
