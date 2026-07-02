<?php

namespace App\Services\Installer;

class RequirementChecker
{
    private array $extensions = [
        'curl',
        'openssl',
        'pdo',
        'bcmath',
        'mbstring',
        'tokenizer',
        'xml',
        'fileinfo',
    ];

    public function check(): array
    {
        $items = [
            [
                'label' => 'PHP 8.0 or higher',
                'ok' => version_compare(PHP_VERSION, '8.0.0', '>='),
                'value' => PHP_VERSION,
            ],
            [
                'label' => 'Storage directory writable',
                'ok' => is_writable(storage_path()),
                'value' => storage_path(),
            ],
            [
                'label' => '.env writable',
                'ok' => is_writable(base_path('.env')) || is_writable(base_path()),
                'value' => base_path('.env'),
            ],
            [
                'label' => 'MySQL PDO support',
                'ok' => extension_loaded('pdo_mysql'),
                'value' => extension_loaded('pdo_mysql') ? 'Available' : 'Missing',
            ],
        ];



        return [
            'items' => $items,
            'passes' => collect($items)->every(fn($item) => $item['ok']),
        ];
    }
}
