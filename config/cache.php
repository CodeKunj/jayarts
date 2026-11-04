<?php
/**
 * JayArt High-Performance Redis-Inspired Custom Cache Configuration
 * Production-ready for cPanel shared hosting without external dependencies
 */

declare(strict_types=1);

return [
    // Global master switch to enable/disable all caching instantly without touching application logic
    'enabled' => true,

    // Storage driver (Redis-inspired file-based engine with atomic locking)
    'driver' => 'file',

    // Dedicated secure filesystem directory for cache blobs
    'storage_path' => defined('CACHE_PATH') 
        ? CACHE_PATH 
        : (defined('ROOT_PATH') ? ROOT_PATH . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'cache' : dirname(__DIR__) . '/storage/cache'),

    // Time-To-Live (TTL) Configurations in Seconds
    'default_ttl'  => 3600,   // 1 Hour (Standard fallback TTL)
    'product_ttl'  => 1800,   // 30 Minutes (Individual product details & active catalog)
    'category_ttl' => 86400,  // 24 Hours (Art mediums & category hierarchies)
    'homepage_ttl' => 900,    // 15 Minutes (Homepage bento grid & featured masterworks)
    'search_ttl'   => 600,    // 10 Minutes (Search query cache)

    // Storage Quotas & Limits
    'max_files'    => 10000,  // Maximum allowable cache files before LRU sweep
    'max_size_mb'  => 150,    // Maximum disk storage in Megabytes (150 MB)

    // Automatic Garbage Collection divisor (1 in X requests cleans expired cache files)
    'gc_divisor'   => 50,
];

