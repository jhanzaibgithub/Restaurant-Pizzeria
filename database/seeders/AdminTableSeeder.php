<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (Schema::hasTable('admin_roles')) {
            DB::table('admin_roles')->updateOrInsert(
                ['id' => 1],
                [
                    'name' => 'Master Admin',
                    'module_access' => null,
                    'status' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        $admin = [
            'id' => 1,
            'f_name' => 'Super',
            'l_name' => 'Admin',
            'phone' => '01759412381',
            'email' => 'admin@admin.com',
            'image' => 'def.png',
            'password' => bcrypt(12345678),
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now()
        ];

        if (Schema::hasColumn('admins', 'admin_role_id')) {
            $admin['admin_role_id'] = 1;
        }

        if (Schema::hasColumn('admins', 'status')) {
            $admin['status'] = 1;
        }

        if (Schema::hasColumn('admins', 'identity_number')) {
            $admin['identity_number'] = '0';
        }

        if (Schema::hasColumn('admins', 'identity_type')) {
            $admin['identity_type'] = 'demo';
        }

        if (Schema::hasColumn('admins', 'identity_image')) {
            $admin['identity_image'] = 'null';
        }

        DB::table('admins')->updateOrInsert(['id' => 1], $admin);
    }
}
