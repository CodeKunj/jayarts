<?php
/**
 * JayArt Native Environment Variable Engine (.env)
 * Lightweight, zero-dependency, cPanel & production ready
 */

declare(strict_types=1);

class Env {
    private static bool $loaded = false;

    /**
     * Load environment variables from a .env file
     */
    public static function load(string $filePath): void {
        if (!file_exists($filePath) || !is_readable($filePath)) {
            return;
        }

        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if ($lines === false) {
            return;
        }

        foreach ($lines as $line) {
            $line = trim($line);

            // Skip comments and empty lines
            if ($line === '' || str_starts_with($line, '#') || str_starts_with($line, ';')) {
                continue;
            }

            // Split on the first '='
            $parts = explode('=', $line, 2);
            if (count($parts) !== 2) {
                continue;
            }

            $key = trim($parts[0]);
            $val = trim($parts[1]);

            // Strip surrounding single or double quotes
            if (
                (str_starts_with($val, '"') && str_ends_with($val, '"')) ||
                (str_starts_with($val, "'") && str_ends_with($val, "'"))
            ) {
                $val = substr($val, 1, -1);
            }

            // Handle inline comments if outside quotes
            if (!str_contains($parts[1], '"') && !str_contains($parts[1], "'")) {
                $commentPos = strpos($val, '#');
                if ($commentPos !== false) {
                    $val = trim(substr($val, 0, $commentPos));
                }
            }

            // Set in environment
            putenv("{$key}={$val}");
            $_ENV[$key] = $val;
            $_SERVER[$key] = $val;
        }

        self::$loaded = true;
    }

    /**
     * Retrieve environment variable with type casting
     */
    public static function get(string $key, mixed $default = null): mixed {
        $val = getenv($key);
        if ($val === false) {
            $val = $_ENV[$key] ?? $_SERVER[$key] ?? null;
        }

        if ($val === null) {
            return $default;
        }

        return match (strtolower((string)$val)) {
            'true', '(true)' => true,
            'false', '(false)' => false,
            'empty', '(empty)' => '',
            'null', '(null)' => null,
            default => $val,
        };
    }

    /**
     * Check if environment file was loaded
     */
    public static function isLoaded(): bool {
        return self::$loaded;
    }
}

/**
 * Global env() helper function
 */
if (!function_exists('env')) {
    function env(string $key, mixed $default = null): mixed {
        return Env::get($key, $default);
    }
}
