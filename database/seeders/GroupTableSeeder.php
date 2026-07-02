<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class GroupTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (! Schema::hasTable('groups')) {
            return;
        }

        $groups = [
            'Indoor',
            'Outdoor',
            'Family',
            'VIP',
            'Terrace',
            'Ground Floor',
            'First Floor',
        ];

        foreach ($groups as $title) {
            DB::table('groups')->updateOrInsert(
                ['title' => $title],
                [
                    'is_available' => 1,
                    'status' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
