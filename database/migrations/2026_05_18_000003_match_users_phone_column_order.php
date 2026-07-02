<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MatchUsersPhoneColumnOrder extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('users') && Schema::hasColumn('users', 'phone')) {
            DB::statement("ALTER TABLE `users` MODIFY `phone` varchar(255) DEFAULT NULL AFTER `email_verification_token`");
        }
    }

    public function down(): void
    {
        //
    }
}
