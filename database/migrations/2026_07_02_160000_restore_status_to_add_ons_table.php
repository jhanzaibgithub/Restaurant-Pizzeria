<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RestoreStatusToAddOnsTable extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('add_ons') && ! Schema::hasColumn('add_ons', 'status')) {
            Schema::table('add_ons', function (Blueprint $table) {
                $table->tinyInteger('status')->default(1);
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('add_ons') && Schema::hasColumn('add_ons', 'status')) {
            Schema::table('add_ons', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }
    }
}
