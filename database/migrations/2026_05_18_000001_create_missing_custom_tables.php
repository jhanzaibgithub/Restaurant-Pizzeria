<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateMissingCustomTables extends Migration
{
    public function up(): void
    {
        DB::statement("CREATE TABLE IF NOT EXISTS `bd_conversations` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `order_id` bigint(20) NOT NULL,
            `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
            `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");

        DB::statement("CREATE TABLE IF NOT EXISTS `bd_messages` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `conversation_id` int(11) NOT NULL,
            `branch_id` int(11) DEFAULT NULL,
            `deliveryman_id` int(11) DEFAULT NULL,
            `message` text NOT NULL,
            `attachment` text NOT NULL,
            `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
            `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");

        DB::statement("CREATE TABLE IF NOT EXISTS `branch_settings` (
            `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            `branch_id` bigint(20) unsigned NOT NULL,
            `restaurant_name` varchar(255) NOT NULL,
            `phone` varchar(255) DEFAULT NULL,
            `email` varchar(255) DEFAULT NULL,
            `address` varchar(255) DEFAULT NULL,
            `country` varchar(255) NOT NULL DEFAULT 'United Kingdom',
            `time_zone` varchar(255) NOT NULL DEFAULT '(UTC+00:00) Edinburgh',
            `time_format` varchar(255) NOT NULL DEFAULT '24 hour',
            `currency` varchar(255) NOT NULL DEFAULT 'USD ($)',
            `currency_position` varchar(255) NOT NULL DEFAULT 'left',
            `digit_after_decimal` int(11) NOT NULL DEFAULT 2,
            `copyright_text` varchar(255) DEFAULT NULL,
            `pagination` int(11) NOT NULL DEFAULT 10,
            `min_order_value` decimal(10,2) NOT NULL DEFAULT 0.00,
            `food_preparation_time` int(11) NOT NULL DEFAULT 30,
            `schedule_order_slot_duration` int(11) NOT NULL DEFAULT 1,
            `latitude` decimal(10,8) DEFAULT NULL,
            `longitude` decimal(11,8) DEFAULT NULL,
            `coverage_km` int(11) NOT NULL DEFAULT 0,
            `self_pickup` tinyint(1) NOT NULL DEFAULT 0,
            `delivery` tinyint(1) NOT NULL DEFAULT 0,
            `email_verification` tinyint(1) NOT NULL DEFAULT 0,
            `phone_verification` tinyint(1) NOT NULL DEFAULT 0,
            `deliveryman_self_registration` tinyint(1) NOT NULL DEFAULT 0,
            `veg_non_veg_option` tinyint(1) NOT NULL DEFAULT 0,
            `status` tinyint(1) NOT NULL DEFAULT 0,
            `fav_icon` varchar(255) DEFAULT NULL,
            `banner_image` varchar(255) DEFAULT NULL,
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`),
            KEY `branch_settings_branch_id_foreign` (`branch_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

        DB::statement("CREATE TABLE IF NOT EXISTS `branch_taxes` (
            `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            `branch_setting_id` bigint(20) unsigned NOT NULL,
            `tax_type` varchar(255) NOT NULL,
            `tax_rate` decimal(5,2) NOT NULL,
            `status` tinyint(1) NOT NULL DEFAULT 1,
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`),
            KEY `branch_taxes_branch_setting_id_foreign` (`branch_setting_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

        DB::statement("CREATE TABLE IF NOT EXISTS `colors` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `name` varchar(30) DEFAULT NULL,
            `code` varchar(10) DEFAULT NULL,
            `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
            `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci");

        DB::statement("CREATE TABLE IF NOT EXISTS `groups` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `title` varchar(191) NOT NULL,
            `is_available` tinyint(4) NOT NULL DEFAULT 1,
            `status` tinyint(4) NOT NULL DEFAULT 1,
            `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
            `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci");

        DB::statement("CREATE TABLE IF NOT EXISTS `kitchens` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `branch_id` int(11) NOT NULL,
            `title` varchar(191) NOT NULL,
            `image` text DEFAULT NULL,
            `status` tinyint(4) NOT NULL DEFAULT 1,
            `default` tinyint(4) NOT NULL DEFAULT 0,
            `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
            `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci");

        DB::statement("CREATE TABLE IF NOT EXISTS `printers` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `kitchen_id` int(11) DEFAULT NULL,
            `branch_id` int(11) NOT NULL,
            `title` varchar(191) DEFAULT NULL,
            `ip` varchar(191) DEFAULT NULL,
            `is_primary` tinyint(1) NOT NULL DEFAULT 0,
            `default` tinyint(4) NOT NULL DEFAULT 0,
            `status` tinyint(4) NOT NULL DEFAULT 1,
            `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
            `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci");

        DB::statement("CREATE TABLE IF NOT EXISTS `sb_conversations` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `branch_id` int(11) NOT NULL,
            `message` varchar(191) DEFAULT NULL,
            `reply` varchar(191) DEFAULT NULL,
            `is_reply` tinyint(4) DEFAULT NULL,
            `image` text DEFAULT NULL,
            `checked` tinyint(4) NOT NULL DEFAULT 0,
            `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
            `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");

        DB::statement("CREATE TABLE IF NOT EXISTS `socket_events` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `event_name` varchar(255) NOT NULL,
            `event_data` text NOT NULL,
            `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
            `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

        DB::statement("CREATE TABLE IF NOT EXISTS `soft_credentials` (
            `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            `key` varchar(255) DEFAULT NULL,
            `value` longtext DEFAULT NULL,
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

        DB::statement("CREATE TABLE IF NOT EXISTS `table_reservations` (
            `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            `table_id` bigint(20) unsigned NOT NULL,
            `branch_table_token` varchar(255) NOT NULL,
            `branch_table_token_is_expired` tinyint(1) NOT NULL DEFAULT 0,
            `user_id` bigint(20) unsigned NOT NULL,
            `order_id` bigint(20) unsigned DEFAULT NULL,
            `date_time` datetime NOT NULL,
            `status` enum('pending','confirmed','canceled') NOT NULL DEFAULT 'pending',
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`),
            UNIQUE KEY `table_reservations_branch_table_token_unique` (`branch_table_token`),
            KEY `table_reservations_table_id_foreign` (`table_id`),
            KEY `table_reservations_user_id_foreign` (`user_id`),
            KEY `table_reservations_order_id_foreign` (`order_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    }

    public function down(): void
    {
        foreach ([
            'table_reservations',
            'soft_credentials',
            'socket_events',
            'sb_conversations',
            'printers',
            'kitchens',
            'groups',
            'colors',
            'branch_taxes',
            'branch_settings',
            'bd_messages',
            'bd_conversations',
        ] as $table) {
            DB::statement("DROP TABLE IF EXISTS `$table`");
        }
    }
}
