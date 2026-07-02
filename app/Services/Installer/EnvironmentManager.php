<?php

namespace App\Services\Installer;

use Illuminate\Support\Facades\File;
use RuntimeException;

class EnvironmentManager
{
    public function update(array $values): void
    {
        $path = base_path('.env');

        if (! File::exists($path)) {
            File::copy(base_path('.env.example'), $path);
        }

        if (! is_writable($path)) {
            throw new RuntimeException('.env file is not writable.');
        }

        $content = File::get($path);

        foreach ($values as $key => $value) {
            $key = strtoupper($key);
            $value = $this->formatValue((string) $value);
            $pattern = "/^{$key}=.*$/m";

            if (preg_match($pattern, $content)) {
                $content = preg_replace($pattern, "{$key}={$value}", $content);
            } else {
                $content .= PHP_EOL . "{$key}={$value}";
            }
        }

        File::put($path, trim($content) . PHP_EOL);
    }

    private function formatValue(string $value): string
    {
        $value = str_replace(["\r", "\n"], '', trim($value));

        if ($value === '' || preg_match('/^[A-Za-z0-9_.,:\/@-]+$/', $value)) {
            return $value;
        }

        return '"' . addcslashes($value, '"\\') . '"';
    }
}
