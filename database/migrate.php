<?php
/**
 * JayArt Database Migration & Seeder CLI/Web Runner
 * Quickly initialize the MySQL database for cPanel or local development
 */

declare(strict_types=1);

require_once dirname(__DIR__) . '/config/config.php';
require_once dirname(__DIR__) . '/config/database.php';

// Check if running from CLI or authorized admin
$isCli = (php_sapi_name() === 'cli');

echo $isCli ? "=== JayArt Database Setup ===\n" : "<h3>JayArt Database Setup</h3><pre>";

try {
    $pdo = Database::connect();
    if (!$pdo) {
        throw new RuntimeException("Could not connect to database with current credentials in config/database.php.");
    }

    echo "Connected successfully to MySQL.\n";

    // 1. Run schema.sql
    $schemaFile = __DIR__ . '/schema.sql';
    if (file_exists($schemaFile)) {
        echo "Importing schema from {$schemaFile}...\n";
        $schemaSql = file_get_contents($schemaFile);
        $pdo->exec($schemaSql);
        echo "✓ Schema imported successfully.\n";
    }

    // 2. Run seed.sql
    $seedFile = __DIR__ . '/seed.sql';
    if (file_exists($seedFile)) {
        echo "Importing seed data from {$seedFile}...\n";
        $seedSql = file_get_contents($seedFile);
        $pdo->exec($seedSql);
        echo "✓ Seed data imported successfully.\n";
    }

    echo "\nDatabase initialization complete!\n";
    echo "Super Admin: admin@jayart.com | Password: admin123\n";

} catch (Throwable $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

if (!$isCli) {
    echo "</pre>";
}

