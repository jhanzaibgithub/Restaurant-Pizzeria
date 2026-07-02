<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AlignSchemaWithFestFlow extends Migration
{
    public function up(): void
    {
        $this->dropColumnIfExists('add_ons', 'status');
        $this->dropColumnIfExists('attributes', 'status');



        if (Schema::hasTable('branches')) {
            $this->addColumnIfMissing('branches', 'fcm_token', "ALTER TABLE `branches` ADD `fcm_token` text DEFAULT NULL AFTER `remember_token`");
            $this->addColumnIfMissing('branches', 'image', "ALTER TABLE `branches` ADD `image` varchar(255) NOT NULL DEFAULT 'def.png' AFTER `fcm_token`");
            $this->addColumnIfMissing('branches', 'auth_token', "ALTER TABLE `branches` ADD `auth_token` text DEFAULT NULL AFTER `cover_image`");
        }

        if (Schema::hasTable('categories')) {
            $this->addColumnIfMissing('categories', 'branch_id', "ALTER TABLE `categories` ADD `branch_id` int(11) DEFAULT NULL AFTER `id`");
            DB::statement("ALTER TABLE `categories`
                MODIFY `parent_id` bigint(11) NOT NULL DEFAULT 0,
                MODIFY `position` int(11) NOT NULL DEFAULT 1");
        }

        if (Schema::hasTable('delivery_men')) {
            DB::statement("ALTER TABLE `delivery_men`
                MODIFY `last_lat` varchar(255) DEFAULT NULL,
                MODIFY `last_long` varchar(255) DEFAULT NULL");
        }

        if (Schema::hasTable('order_details')) {
            $this->addColumnIfMissing('order_details', 'complimentary', "ALTER TABLE `order_details` ADD `complimentary` tinyint(1) DEFAULT NULL AFTER `variant`");
            $this->addColumnIfMissing('order_details', 'comment', "ALTER TABLE `order_details` ADD `comment` varchar(191) DEFAULT NULL AFTER `complimentary`");
        }

        if (Schema::hasTable('orders')) {
            $this->addColumnIfMissing('orders', 'discount', "ALTER TABLE `orders` ADD `discount` varchar(20) DEFAULT NULL AFTER `total_tax_amount`");
            $this->addColumnIfMissing('orders', 'discount_type', "ALTER TABLE `orders` ADD `discount_type` varchar(191) DEFAULT NULL AFTER `discount`");
            $this->addColumnIfMissing('orders', 'requested_driver_id', "ALTER TABLE `orders` ADD `requested_driver_id` text DEFAULT NULL AFTER `delivery_man_id`");
            DB::statement("ALTER TABLE `orders`
                MODIFY `delivery_charge` decimal(8,2) DEFAULT NULL,
                MODIFY `order_confirmation_image` varchar(255) DEFAULT NULL");
            $this->addColumnIfMissing('orders', 'wolt_promise_id', "ALTER TABLE `orders` ADD `wolt_promise_id` varchar(255) DEFAULT NULL AFTER `order_confirmation_image`");
            $this->addColumnIfMissing('orders', 'wolt_driver', "ALTER TABLE `orders` ADD `wolt_driver` tinyint(4) NOT NULL DEFAULT 0 AFTER `wolt_promise_id`");
            $this->addColumnIfMissing('orders', 'wolt_tracking_url', "ALTER TABLE `orders` ADD `wolt_tracking_url` varchar(255) DEFAULT NULL AFTER `wolt_driver`");
            $this->addColumnIfMissing('orders', 'last_assignment_time', "ALTER TABLE `orders` ADD `last_assignment_time` timestamp NULL DEFAULT NULL AFTER `wolt_tracking_url`");
            $this->addColumnIfMissing('orders', 'foodpanda_order_id', "ALTER TABLE `orders` ADD `foodpanda_order_id` int(11) DEFAULT NULL AFTER `last_assignment_time`");
            $this->addColumnIfMissing('orders', 'pedalo_tracking_id', "ALTER TABLE `orders` ADD `pedalo_tracking_id` int(11) DEFAULT NULL AFTER `foodpanda_order_id`");
            $this->addColumnIfMissing('orders', 'pedalo_tracking_url', "ALTER TABLE `orders` ADD `pedalo_tracking_url` text DEFAULT NULL AFTER `pedalo_tracking_id`");
            $this->addColumnIfMissing('orders', 'tracking_url', "ALTER TABLE `orders` ADD `tracking_url` text DEFAULT NULL AFTER `pedalo_tracking_url`");
            $this->addColumnIfMissing('orders', 'delivery_system', "ALTER TABLE `orders` ADD `delivery_system` varchar(191) DEFAULT NULL AFTER `tracking_url`");
            $this->addColumnIfMissing('orders', 'stuart_tracking_id', "ALTER TABLE `orders` ADD `stuart_tracking_id` int(11) DEFAULT NULL AFTER `delivery_system`");
            $this->addColumnIfMissing('orders', 'stuart_tracking_url', "ALTER TABLE `orders` ADD `stuart_tracking_url` text DEFAULT NULL AFTER `stuart_tracking_id`");
            $this->addColumnIfMissing('orders', 'foodpanda_tracking_id', "ALTER TABLE `orders` ADD `foodpanda_tracking_id` int(11) DEFAULT NULL AFTER `stuart_tracking_url`");
            $this->addColumnIfMissing('orders', 'foodpanda_tracking_url', "ALTER TABLE `orders` ADD `foodpanda_tracking_url` text DEFAULT NULL AFTER `foodpanda_tracking_id`");
            $this->addColumnIfMissing('orders', 'justeat_tracking_id', "ALTER TABLE `orders` ADD `justeat_tracking_id` int(11) DEFAULT NULL AFTER `foodpanda_tracking_url`");
            $this->addColumnIfMissing('orders', 'justeat_tracking_url', "ALTER TABLE `orders` ADD `justeat_tracking_url` text DEFAULT NULL AFTER `justeat_tracking_id`");
            $this->addColumnIfMissing('orders', 'notified_at', "ALTER TABLE `orders` ADD `notified_at` datetime DEFAULT NULL AFTER `justeat_tracking_url`");
        }

        if (Schema::hasTable('password_resets')) {
            $this->addColumnIfMissing('password_resets', 'phone', "ALTER TABLE `password_resets` ADD `phone` varchar(255) DEFAULT NULL AFTER `created_at`");
        }

        if (Schema::hasTable('product_by_branches')) {
            DB::statement("ALTER TABLE `product_by_branches`
                MODIFY `discount_type` varchar(255) DEFAULT NULL,
                MODIFY `discount` double(8,2) DEFAULT NULL");
        }

        if (Schema::hasTable('products')) {
            $this->addColumnIfMissing('products', 'kitchen_id', "ALTER TABLE `products` ADD `kitchen_id` int(11) DEFAULT NULL AFTER `id`");
            $this->addColumnIfMissing('products', 'category_id', "ALTER TABLE `products` ADD `category_id` int(11) NOT NULL AFTER `kitchen_id`");
            DB::statement("ALTER TABLE `products`
                MODIFY `image` text DEFAULT NULL,
                MODIFY `discount` decimal(8,2) DEFAULT NULL,
                MODIFY `discount_type` varchar(20) DEFAULT NULL,
                MODIFY `tax_type` varchar(20) DEFAULT NULL");
            $this->addColumnIfMissing('products', 'branch_id', "ALTER TABLE `products` ADD `branch_id` bigint(20) NOT NULL DEFAULT 1 AFTER `set_menu`");
            $this->addColumnIfMissing('products', 'colors', "ALTER TABLE `products` ADD `colors` text DEFAULT NULL AFTER `branch_id`");
            $this->addColumnIfMissing('products', 'tags', "ALTER TABLE `products` ADD `tags` text DEFAULT NULL AFTER `priority`");
            $this->addColumnIfMissing('products', 'stock', "ALTER TABLE `products` ADD `stock` int(11) DEFAULT 0 AFTER `tags`");
        }

        if (Schema::hasTable('tables')) {
            $this->addColumnIfMissing('tables', 'group_id', "ALTER TABLE `tables` ADD `group_id` int(11) NOT NULL AFTER `id`");
            DB::statement("ALTER TABLE `tables` MODIFY `number` varchar(191) NOT NULL");
            $this->addColumnIfMissing('tables', 'is_available', "ALTER TABLE `tables` ADD `is_available` tinyint(4) NOT NULL DEFAULT 1 AFTER `is_active`");
        }

        if (Schema::hasTable('users')) {
            DB::statement("ALTER TABLE `users` MODIFY `phone` varchar(255) DEFAULT NULL");
        }
    }

    public function down(): void
    {
        //
    }

    private function addColumnIfMissing(string $table, string $column, string $statement): void
    {
        if (Schema::hasTable($table) && ! Schema::hasColumn($table, $column)) {
            DB::statement($statement);
        }
    }

    private function dropColumnIfExists(string $table, string $column): void
    {
        if (Schema::hasTable($table) && Schema::hasColumn($table, $column)) {
            DB::statement("ALTER TABLE `$table` DROP COLUMN `$column`");
        }
    }
}
