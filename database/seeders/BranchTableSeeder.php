<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class BranchTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (! Schema::hasTable('branches')) {
            return;
        }

        $branch = [
            'id' => 1,
            'restaurant_id' => 1,
            'name' => 'Main Branch',
            'email' => 'mainb@mainb.com',
            'password' => bcrypt(12345678),
            'latitude' => '24.8607',
            'longitude' => '67.0011',
            'address' => 'Main demo branch',
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        if (Schema::hasColumn('branches', 'phone')) {
            $branch['phone'] = '01759412382';
        }

        if (Schema::hasColumn('branches', 'branch_promotion_status')) {
            $branch['branch_promotion_status'] = 1;
        }

        if (Schema::hasColumn('branches', 'coverage')) {
            $branch['coverage'] = 10;
        }

        if (Schema::hasColumn('branches', 'remember_token')) {
            $branch['remember_token'] = Str::random(60);
        }

        if (Schema::hasColumn('branches', 'cover_image')) {
            $branch['cover_image'] = null;
        }

        DB::table('branches')->updateOrInsert(['id' => 1], $branch);
    }
}
