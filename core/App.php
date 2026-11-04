<?php
/**
 * JayArt Core Application Bootstrap & Autoloader
 * Lightweight, zero-dependency, cPanel ready
 */

declare(strict_types=1);

class App {
    private static bool $bootstrapped = false;

    /**
     * Bootstrap the Application
     */
    public static function bootstrap(): void {
        if (self::$bootstrapped) {
            return;
        }

        // Ensure ROOT_PATH is defined first
        if (!defined('ROOT_PATH')) {
            define('ROOT_PATH', dirname(__DIR__));
        }

        // 1. Load Environment Variables (.env)
        require_once ROOT_PATH . '/core/Env.php';
        if (file_exists(ROOT_PATH . '/.env')) {
            Env::load(ROOT_PATH . '/.env');
        }

        // 2. Load core configuration
        $root = ROOT_PATH;
        require_once $root . '/config/config.php';
        require_once $root . '/config/database.php';
        require_once $root . '/core/Cache.php';

        // Register class autoloader
        spl_autoload_register([self::class, 'autoload']);

        self::$bootstrapped = true;
    }

    /**
     * Autoload Classes from core/, app/controllers/, app/models/, admin/controllers/
     */
    public static function autoload(string $className): void {
        $paths = [
            CORE_PATH . DIRECTORY_SEPARATOR . $className . '.php',
            APP_PATH . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . $className . '.php',
            APP_PATH . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR . $className . '.php',
            ADMIN_PATH . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR . $className . '.php',
        ];

        foreach ($paths as $path) {
            if (file_exists($path)) {
                require_once $path;
                return;
            }
        }
    }
}

