<?php
/**
 * JayArt Redis-Inspired Custom High-Performance Cache Engine
 * File-backed caching with atomic writes, concurrency locks, TTL expiry, pattern invalidation, and metrics.
 * Designed for production shared hosting (cPanel) without external server dependencies.
 */

declare(strict_types=1);

class Cache {

    private static ?array $config = null;
    private static bool $memoryCachedStatsLoaded = false;
    private static array $runtimeStats = [
        'hits'   => 0,
        'misses' => 0,
        'writes' => 0,
        'deletes'=> 0,
    ];

    /**
     * Load & normalize cache configuration
     */
    public static function getConfig(): array {
        if (self::$config === null) {
            $defaultConfigPath = defined('CONFIG_PATH') ? CONFIG_PATH . '/cache.php' : dirname(__DIR__) . '/config/cache.php';
            $loaded = file_exists($defaultConfigPath) ? (require $defaultConfigPath) : [];

            $storageDir = $loaded['storage_path'] ?? (defined('CACHE_PATH') ? CACHE_PATH : dirname(__DIR__) . '/storage/cache');

            self::$config = [
                'enabled'      => (bool)($loaded['enabled'] ?? true),
                'driver'       => $loaded['driver'] ?? 'file',
                'storage_path' => rtrim($storageDir, '\\/'),
                'default_ttl'  => (int)($loaded['default_ttl'] ?? 3600),
                'product_ttl'  => (int)($loaded['product_ttl'] ?? 1800),
                'category_ttl' => (int)($loaded['category_ttl'] ?? 86400),
                'homepage_ttl' => (int)($loaded['homepage_ttl'] ?? 900),
                'search_ttl'   => (int)($loaded['search_ttl'] ?? 600),
                'max_files'    => (int)($loaded['max_files'] ?? 10000),
                'max_size_mb'  => (int)($loaded['max_size_mb'] ?? 150),
                'gc_divisor'   => (int)($loaded['gc_divisor'] ?? 50),
            ];

            // Check if dynamically overridden in Setting database
            if (class_exists('Setting')) {
                $dbEnabled = Setting::get('cache_enabled');
                if ($dbEnabled !== null) {
                    self::$config['enabled'] = ($dbEnabled === '1' || $dbEnabled === 'true');
                }
                $dbProductTtl = Setting::get('cache_product_ttl');
                if (!empty($dbProductTtl) && is_numeric($dbProductTtl)) {
                    self::$config['product_ttl'] = (int)$dbProductTtl;
                }
                $dbCatTtl = Setting::get('cache_category_ttl');
                if (!empty($dbCatTtl) && is_numeric($dbCatTtl)) {
                    self::$config['category_ttl'] = (int)$dbCatTtl;
                }
                $dbHomeTtl = Setting::get('cache_homepage_ttl');
                if (!empty($dbHomeTtl) && is_numeric($dbHomeTtl)) {
                    self::$config['homepage_ttl'] = (int)$dbHomeTtl;
                }
            }

            self::ensureStorageDirectory();
        }

        return self::$config;
    }

    /**
     * Check if cache is globally enabled
     */
    public static function isEnabled(): bool {
        return self::getConfig()['enabled'];
    }

    /**
     * Dynamically toggle cache state
     */
    public static function setEnabled(bool $enabled): void {
        self::getConfig();
        self::$config['enabled'] = $enabled;
    }

    /**
     * Get storage path
     */
    public static function getStoragePath(): string {
        return self::getConfig()['storage_path'];
    }

    /**
     * Ensure storage directory exists and is secured
     */
    private static function ensureStorageDirectory(): void {
        $dir = self::$config['storage_path'];
        if (!is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }

        // Write security .htaccess if missing
        $htPath = $dir . DIRECTORY_SEPARATOR . '.htaccess';
        if (!file_exists($htPath)) {
            @file_put_contents($htPath, "Deny from all\nOptions -Indexes\n");
        }

        // Write index.php guard if missing
        $idxPath = $dir . DIRECTORY_SEPARATOR . 'index.php';
        if (!file_exists($idxPath)) {
            @file_put_contents($idxPath, "<?php http_response_code(403); exit('Access Denied');");
        }
    }

    /**
     * Generate safe, hashed filename preventing path traversal
     */
    private static function getFilePath(string $key): string {
        // Strip out dangerous traversal strings
        $sanitizedKey = str_replace(["\0", '../', '..\\'], '', $key);
        
        $prefix = explode(':', $sanitizedKey)[0] ?? 'general';
        $safePrefix = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $prefix) ?: 'general';
        $hash = hash('sha256', $sanitizedKey);

        return self::getStoragePath() . DIRECTORY_SEPARATOR . $safePrefix . '__' . $hash . '.cache';
    }

    /**
     * Retrieve an item from the cache
     */
    public static function get(string $key, mixed $default = null): mixed {
        if (!self::isEnabled()) {
            return $default;
        }

        $filePath = self::getFilePath($key);
        if (!file_exists($filePath)) {
            self::incrementStat('misses');
            return $default;
        }

        $fp = @fopen($filePath, 'rb');
        if (!$fp) {
            self::incrementStat('misses');
            return $default;
        }

        // Shared read lock
        @flock($fp, LOCK_SH);
        $content = @stream_get_contents($fp);
        @flock($fp, LOCK_UN);
        @fclose($fp);

        if ($content === false || $content === '') {
            @unlink($filePath);
            self::incrementStat('misses');
            return $default;
        }

        $payload = json_decode($content, true);

        // Validation against corrupted or incomplete cache files
        if (!is_array($payload) || !isset($payload['k'], $payload['e'], $payload['v'])) {
            @unlink($filePath);
            self::incrementStat('misses');
            return $default;
        }

        // Automatic TTL Expiration Check
        if ($payload['e'] > 0 && time() > $payload['e']) {
            @unlink($filePath);
            self::incrementStat('misses');
            return $default;
        }

        self::incrementStat('hits');
        return $payload['v'];
    }

    /**
     * Store an item in the cache with atomic file locking
     */
    public static function set(string $key, mixed $value, ?int $ttl = null): bool {
        if (!self::isEnabled()) {
            return false;
        }

        // Never cache null or broken errors
        if ($value === null) {
            return false;
        }

        $config = self::getConfig();
        $ttl = ($ttl !== null) ? $ttl : $config['default_ttl'];
        if ($ttl === 0) {
            $expiresAt = 0; // 0 indicates permanent cache
        } elseif ($ttl > 0) {
            $expiresAt = time() + $ttl;
        } else {
            $expiresAt = 1; // Expired immediately in the past
        }

        $payload = [
            'k'   => $key,
            't'   => time(),
            'e'   => $expiresAt,
            'ttl' => $ttl,
            'v'   => $value
        ];

        $json = json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        if ($json === false) {
            return false;
        }

        $cacheDir = self::getStoragePath();
        $finalPath = self::getFilePath($key);

        // Atomic write: write to unique temp file first, then atomic rename
        $tempFile = $cacheDir . DIRECTORY_SEPARATOR . 'tmp_' . bin2hex(random_bytes(8)) . '_' . mt_rand() . '.tmp';
        $fp = @fopen($tempFile, 'wb');
        if (!$fp) {
            return false;
        }

        if (@flock($fp, LOCK_EX)) {
            @fwrite($fp, $json);
            @fflush($fp);
            @flock($fp, LOCK_UN);
            @fclose($fp);

            if (!@rename($tempFile, $finalPath)) {
                @unlink($tempFile);
                return false;
            }

            @chmod($finalPath, 0644);
            self::incrementStat('writes');

            // Occasional automatic garbage collection
            if ($config['gc_divisor'] > 0 && mt_rand(1, $config['gc_divisor']) === 1) {
                self::cleanExpired();
            }

            return true;
        }

        @fclose($fp);
        @unlink($tempFile);
        return false;
    }

    /**
     * Delete an item by exact key
     */
    public static function delete(string $key): bool {
        $filePath = self::getFilePath($key);
        if (file_exists($filePath)) {
            $deleted = @unlink($filePath);
            if ($deleted) {
                self::incrementStat('deletes');
            }
            return $deleted;
        }
        return false;
    }

    /**
     * Redis-like Pattern Invalidation (e.g., 'products:*', 'categories:*', 'home:*')
     * Matches against the internal key or file prefix
     */
    public static function deletePattern(string $pattern): int {
        if ($pattern === '*' || $pattern === '') {
            $count = self::countCacheFiles();
            self::clear();
            return $count;
        }

        $cacheDir = self::getStoragePath();
        if (!is_dir($cacheDir)) {
            return 0;
        }

        // If pattern starts with a prefix before wildcard (e.g., 'products:*')
        $prefix = explode(':', $pattern)[0];
        $safePrefix = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $prefix);

        // Build glob search pattern
        $globPattern = $cacheDir . DIRECTORY_SEPARATOR . ($safePrefix !== '' ? $safePrefix . '__*.cache' : '*.cache');
        $files = glob($globPattern) ?: [];

        // Compile regex for exact pattern matching
        $regex = '/^' . str_replace(['\*', '\?'], ['.*', '.'], preg_quote($pattern, '/')) . '$/i';

        $deletedCount = 0;
        foreach ($files as $file) {
            if (!is_file($file) || basename($file) === '.htaccess' || basename($file) === 'index.php') {
                continue;
            }

            // Inspect stored key in cache file
            $matched = false;
            $fp = @fopen($file, 'rb');
            if ($fp) {
                @flock($fp, LOCK_SH);
                $line = @fgets($fp, 512); // Keys are at the very top of payload
                @flock($fp, LOCK_UN);
                @fclose($fp);

                if ($line && preg_match('/"k"\s*:\s*"([^"]+)"/', $line, $m)) {
                    if (preg_match($regex, $m[1])) {
                        $matched = true;
                    }
                } else {
                    // Fallback to full decode if key wasn't in first 512 bytes
                    $content = @file_get_contents($file);
                    if ($content) {
                        $p = json_decode($content, true);
                        if (is_array($p) && isset($p['k']) && preg_match($regex, (string)$p['k'])) {
                            $matched = true;
                        }
                    }
                }
            }

            if ($matched) {
                if (@unlink($file)) {
                    $deletedCount++;
                }
            }
        }

        if ($deletedCount > 0) {
            self::incrementStat('deletes', $deletedCount);
        }

        return $deletedCount;
    }

    /**
     * Check if a key exists in cache and has not expired
     */
    public static function has(string $key): bool {
        if (!self::isEnabled()) {
            return false;
        }

        $filePath = self::getFilePath($key);
        if (!file_exists($filePath)) {
            return false;
        }

        // Fast header read to check expiration without full payload decode
        $fp = @fopen($filePath, 'rb');
        if (!$fp) return false;
        @flock($fp, LOCK_SH);
        $header = @fgets($fp, 256);
        @flock($fp, LOCK_UN);
        @fclose($fp);

        if ($header && preg_match('/"e"\s*:\s*(\d+)/', $header, $m)) {
            $expiresAt = (int)$m[1];
            if ($expiresAt > 0 && time() > $expiresAt) {
                @unlink($filePath);
                return false;
            }
            return true;
        }

        return self::get($key) !== null;
    }

    /**
     * Cache remember wrapper: return cached or compute & store
     */
    public static function remember(string $key, ?int $ttl, callable $callback): mixed {
        if (!self::isEnabled()) {
            return $callback();
        }

        $cached = self::get($key);
        if ($cached !== null) {
            return $cached;
        }

        $fresh = $callback();
        if ($fresh !== null) {
            self::set($key, $fresh, $ttl);
        }

        return $fresh;
    }

    /**
     * Flush all cache files
     */
    public static function clear(): bool {
        $cacheDir = self::getStoragePath();
        if (!is_dir($cacheDir)) {
            return true;
        }

        $files = glob($cacheDir . DIRECTORY_SEPARATOR . '*.cache') ?: [];
        $tmpFiles = glob($cacheDir . DIRECTORY_SEPARATOR . '*.tmp') ?: [];

        foreach (array_merge($files, $tmpFiles) as $f) {
            if (is_file($f)) {
                @unlink($f);
            }
        }

        // Reset stats
        self::saveStatsFile([
            'hits'        => 0,
            'misses'      => 0,
            'writes'      => 0,
            'deletes'     => 0,
            'last_cleared'=> date('Y-m-d H:i:s')
        ]);

        return true;
    }

    /**
     * Automated Garbage Collection for expired cache files
     */
    public static function cleanExpired(): int {
        $cacheDir = self::getStoragePath();
        if (!is_dir($cacheDir)) {
            return 0;
        }

        $files = glob($cacheDir . DIRECTORY_SEPARATOR . '*.cache') ?: [];
        $now = time();
        $cleaned = 0;

        foreach ($files as $file) {
            $fp = @fopen($file, 'rb');
            if ($fp) {
                @flock($fp, LOCK_SH);
                $header = @fgets($fp, 256);
                @flock($fp, LOCK_UN);
                @fclose($fp);

                if ($header && preg_match('/"e"\s*:\s*(\d+)/', $header, $m)) {
                    $expiresAt = (int)$m[1];
                    if ($expiresAt > 0 && $now > $expiresAt) {
                        if (@unlink($file)) {
                            $cleaned++;
                        }
                    }
                }
            }
        }

        // Clean stale .tmp files older than 5 minutes
        $tmps = glob($cacheDir . DIRECTORY_SEPARATOR . '*.tmp') ?: [];
        foreach ($tmps as $tmp) {
            if (is_file($tmp) && ($now - filemtime($tmp)) > 300) {
                @unlink($tmp);
            }
        }

        return $cleaned;
    }

    /**
     * Count total active cache files
     */
    public static function countCacheFiles(): int {
        $cacheDir = self::getStoragePath();
        if (!is_dir($cacheDir)) return 0;
        $files = glob($cacheDir . DIRECTORY_SEPARATOR . '*.cache');
        return is_array($files) ? count($files) : 0;
    }

    /**
     * Compute comprehensive telemetry, hit-ratio, and storage metrics
     */
    public static function stats(): array {
        $config = self::getConfig();
        $cacheDir = self::getStoragePath();
        $persisted = self::loadStatsFile();

        $files = is_dir($cacheDir) ? (glob($cacheDir . DIRECTORY_SEPARATOR . '*.cache') ?: []) : [];
        $totalBytes = 0;
        $modules = [];
        $keyPreviews = [];

        foreach ($files as $file) {
            $size = filesize($file) ?: 0;
            $totalBytes += $size;

            $base = basename($file);
            $parts = explode('__', $base);
            $module = $parts[0] ?? 'general';
            $modules[$module] = ($modules[$module] ?? 0) + 1;

            // Sample top active keys (up to 15)
            if (count($keyPreviews) < 15) {
                $content = @file_get_contents($file);
                if ($content) {
                    $p = json_decode($content, true);
                    if (is_array($p) && isset($p['k'])) {
                        $remaining = ($p['e'] > 0) ? max(0, $p['e'] - time()) : 'Permanent';
                        $keyPreviews[] = [
                            'key'       => $p['k'],
                            'module'    => $module,
                            'ttl_left'  => is_int($remaining) ? ($remaining . 's') : $remaining,
                            'size'      => self::formatBytes($size),
                            'created_at'=> date('Y-m-d H:i:s', (int)($p['t'] ?? time())),
                            'is_expired'=> ($p['e'] > 0 && time() > $p['e']),
                        ];
                    }
                }
            }
        }

        $hits = (int)($persisted['hits'] ?? 0) + self::$runtimeStats['hits'];
        $misses = (int)($persisted['misses'] ?? 0) + self::$runtimeStats['misses'];
        $writes = (int)($persisted['writes'] ?? 0) + self::$runtimeStats['writes'];
        $deletes = (int)($persisted['deletes'] ?? 0) + self::$runtimeStats['deletes'];

        $totalRequests = $hits + $misses;
        $hitRatio = ($totalRequests > 0) ? round(($hits / $totalRequests) * 100, 1) : 0.0;

        return [
            'enabled'              => $config['enabled'],
            'driver'               => 'High-Performance Atomic File Engine (Redis-Like)',
            'storage_path'         => $cacheDir,
            'is_protected'         => file_exists($cacheDir . DIRECTORY_SEPARATOR . '.htaccess'),
            'total_files'          => count($files),
            'total_size_bytes'     => $totalBytes,
            'total_size_formatted' => self::formatBytes($totalBytes),
            'hits'                 => $hits,
            'misses'               => $misses,
            'writes'               => $writes,
            'deletes'              => $deletes,
            'hit_ratio'            => $hitRatio,
            'modules_breakdown'    => $modules,
            'recent_keys'          => $keyPreviews,
            'ttls'                 => [
                'default'  => $config['default_ttl'],
                'product'  => $config['product_ttl'],
                'category' => $config['category_ttl'],
                'homepage' => $config['homepage_ttl'],
                'search'   => $config['search_ttl'],
            ],
            'last_cleared'         => $persisted['last_cleared'] ?? 'Never',
        ];
    }

    /**
     * Increment persistent metric
     */
    private static function incrementStat(string $type, int $qty = 1): void {
        self::$runtimeStats[$type] = (self::$runtimeStats[$type] ?? 0) + $qty;
        
        // Batch flush every 10 operations or end of request
        if ((self::$runtimeStats['hits'] + self::$runtimeStats['misses'] + self::$runtimeStats['writes']) % 10 === 0) {
            self::flushRuntimeStats();
        }
    }

    private static function getStatsFilePath(): string {
        return self::getStoragePath() . DIRECTORY_SEPARATOR . '.stats.json';
    }

    private static function loadStatsFile(): array {
        $path = self::getStatsFilePath();
        if (file_exists($path)) {
            $data = @file_get_contents($path);
            if ($data) {
                $decoded = json_decode($data, true);
                if (is_array($decoded)) {
                    return $decoded;
                }
            }
        }
        return ['hits' => 0, 'misses' => 0, 'writes' => 0, 'deletes' => 0];
    }

    private static function saveStatsFile(array $stats): void {
        $path = self::getStatsFilePath();
        $temp = $path . '.tmp.' . mt_rand();
        $fp = @fopen($temp, 'wb');
        if ($fp) {
            if (@flock($fp, LOCK_EX)) {
                @fwrite($fp, json_encode($stats, JSON_PRETTY_PRINT));
                @fflush($fp);
                @flock($fp, LOCK_UN);
                @fclose($fp);
                @rename($temp, $path);
                return;
            }
            @fclose($fp);
            @unlink($temp);
        }
    }

    public static function flushRuntimeStats(): void {
        if (self::$runtimeStats['hits'] === 0 && self::$runtimeStats['misses'] === 0 && self::$runtimeStats['writes'] === 0 && self::$runtimeStats['deletes'] === 0) {
            return;
        }

        $persisted = self::loadStatsFile();
        $persisted['hits'] = ($persisted['hits'] ?? 0) + self::$runtimeStats['hits'];
        $persisted['misses'] = ($persisted['misses'] ?? 0) + self::$runtimeStats['misses'];
        $persisted['writes'] = ($persisted['writes'] ?? 0) + self::$runtimeStats['writes'];
        $persisted['deletes'] = ($persisted['deletes'] ?? 0) + self::$runtimeStats['deletes'];

        self::$runtimeStats = ['hits' => 0, 'misses' => 0, 'writes' => 0, 'deletes' => 0];
        self::saveStatsFile($persisted);
    }

    /**
     * Format bytes into readable string (KB, MB)
     */
    public static function formatBytes(int $bytes): string {
        if ($bytes < 1024) return $bytes . ' B';
        if ($bytes < 1048576) return round($bytes / 1024, 1) . ' KB';
        return round($bytes / 1048576, 2) . ' MB';
    }
}

// ====================================================================
// Global Helper Functions (Matching Prompt Specification)
// ====================================================================

if (!function_exists('cacheGet')) {
    function cacheGet(string $key, mixed $default = null): mixed {
        return Cache::get($key, $default);
    }
}

if (!function_exists('cacheSet')) {
    function cacheSet(string $key, mixed $value, ?int $ttl = null): bool {
        return Cache::set($key, $value, $ttl);
    }
}

if (!function_exists('cacheDelete')) {
    function cacheDelete(string $key): bool {
        return Cache::delete($key);
    }
}

if (!function_exists('cacheDeletePattern')) {
    function cacheDeletePattern(string $pattern): int {
        return Cache::deletePattern($pattern);
    }
}

if (!function_exists('cacheClear')) {
    function cacheClear(): bool {
        return Cache::clear();
    }
}

if (!function_exists('cacheExists')) {
    function cacheExists(string $key): bool {
        return Cache::has($key);
    }
}

if (!function_exists('cacheRemember')) {
    function cacheRemember(string $key, ?int $ttl, callable $callback): mixed {
        return Cache::remember($key, $ttl, $callback);
    }
}

if (!function_exists('cacheStats')) {
    function cacheStats(): array {
        return Cache::stats();
    }
}

// Automatically flush stats on script termination
register_shutdown_function([Cache::class, 'flushRuntimeStats']);

