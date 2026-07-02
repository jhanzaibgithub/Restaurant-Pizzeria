<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class DropUsersPhoneUniqueIndex extends Migration
{
    public function up(): void
    {
        $index = DB::table('information_schema.STATISTICS')
            ->where('TABLE_SCHEMA', DB::raw('DATABASE()'))
            ->where('TABLE_NAME', 'users')
            ->where('INDEX_NAME', 'users_phone_unique')
            ->exists();

        if ($index) {
            DB::statement('ALTER TABLE `users` DROP INDEX `users_phone_unique`');
        }
    }

    public function down(): void
    {
        //
    }
}
