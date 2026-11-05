<?php
/**
 * JayArt E-Commerce Platform
 * Database Configuration & Connection (PDO)
 */

declare(strict_types=1);

class Database {
    // Database credentials - easily editable for cPanel MySQL
    private static string $host = '127.0.0.1';
    private static string $port = '3306';
    private static string $dbName = 'jayarts';
    private static string $username = 'root';
    private static string $password = '';
    private static string $charset = 'utf8mb4';

    private static ?PDO $pdo = null;
    private static bool $connectionAttempted = false;

    /**
     * Get Singleton PDO Instance
     */
    public static function connect(): ?PDO {
        if (self::$pdo !== null) {
            return self::$pdo;
        }

        if (self::$connectionAttempted) {
            return null; // Return immediately without repeated socket connection timeouts
        }

        self::$connectionAttempted = true;

        // Check for environment overrides (e.g. from cPanel .env or server config)
        $host    = (string)env('DB_HOST', self::$host);
        $port    = (string)env('DB_PORT', self::$port);
        $dbName  = (string)env('DB_NAME', self::$dbName);
        $user    = (string)env('DB_USER', self::$username);
        $pass    = (string)env('DB_PASS', self::$password);
        $charset = (string)env('DB_CHARSET', self::$charset);

        $dsn = "mysql:host={$host};port={$port};dbname={$dbName};charset={$charset}";

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
            PDO::ATTR_TIMEOUT            => 5, // 5s timeout for reliable shared hosting
        ];

        if (defined('PDO::MYSQL_ATTR_MULTI_STATEMENTS')) {
            $options[PDO::MYSQL_ATTR_MULTI_STATEMENTS] = true;
        }

        try {
            self::$pdo = new PDO($dsn, $user, $pass, $options);
            self::$pdo->exec("SET NAMES {$charset} COLLATE utf8mb4_unicode_ci");
            self::autoMigrateIfEmpty(self::$pdo);
            return self::$pdo;
        } catch (Throwable $e) {
            error_log('Database Connection Notice: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Auto-migrate schema and initial seeds if database is empty (cPanel self-healing)
     */
    public static function autoMigrateIfEmpty(?PDO $pdo = null, bool $force = false): void {
        static $checked = false;
        if ($checked && !$force) return;
        $checked = true;

        $db = $pdo ?? self::$pdo;
        if (!$db) return;

        try {
            if (!$force) {
                $check = $db->query("SHOW TABLES LIKE 'products'");
                if ($check && $check->rowCount() > 0) {
                    return; // Products table already exists
                }
            }

            $schemaFile = defined('ROOT_PATH') ? ROOT_PATH . '/database/schema.sql' : dirname(__DIR__) . '/database/schema.sql';
            self::executeSqlFile($db, $schemaFile);

            $seedFile = defined('ROOT_PATH') ? ROOT_PATH . '/database/seed.sql' : dirname(__DIR__) . '/database/seed.sql';
            self::executeSqlFile($db, $seedFile);
        } catch (Throwable $e) {
            error_log('Database auto-migrate notice: ' . $e->getMessage());
        }
    }

    /**
     * Execute SQL file splitting statements cleanly and ignoring comments
     */
    public static function executeSqlFile(PDO $db, string $filePath): bool {
        if (!file_exists($filePath)) {
            return false;
        }

        $content = file_get_contents($filePath);
        if ($content === false || trim($content) === '') {
            return false;
        }

        // Clean single line comments
        $content = preg_replace('/^--[^\r\n]*/m', '', $content);
        // Clean block comments
        $content = preg_replace('/\/\*.*?\*\//s', '', $content);

        // Split statements by semicolon
        $statements = preg_split('/;\s*[\r\n]+/', $content);
        if (!$statements) {
            $statements = explode(';', $content);
        }

        $db->exec("SET FOREIGN_KEY_CHECKS = 0;");
        foreach ($statements as $stmt) {
            $sql = trim($stmt);
            if ($sql === '') {
                continue;
            }
            try {
                $db->exec($sql);
            } catch (Throwable $e) {
                error_log("SQL auto-exec notice: " . $e->getMessage());
            }
        }
        $db->exec("SET FOREIGN_KEY_CHECKS = 1;");
        return true;
    }

    /**
     * Set Custom Connection Settings at Runtime (Useful for cPanel Installers)
     */
    public static function configure(string $host, string $dbName, string $username, string $password, string $port = '3306'): void {
        self::$host = $host;
        self::$dbName = $dbName;
        self::$username = $username;
        self::$password = $password;
        self::$port = $port;
        self::$pdo = null; // Reset connection
    }

    /**
     * Check if database connection is currently alive
     */
    public static function isConnected(): bool {
        try {
            $pdo = self::connect();
            return $pdo !== null;
        } catch (Throwable $e) {
            return false;
        }
    }
}
