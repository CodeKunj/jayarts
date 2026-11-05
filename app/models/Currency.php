<?php
/**
 * JayArt Multi-Currency Model
 * Handles Global FX Rates, Currency Conversion, Dynamic Formatting & Admin Management
 */

declare(strict_types=1);

class Currency extends Model {
    protected string $table = 'currencies';

    private static ?array $cachedAll = null;
    private static ?array $activeCurrency = null;

    /**
     * Default Currencies (Fallback if DB is unavailable)
     * Base Currency is Indian Rupee (INR)
     */
    public static function getDefaults(): array {
        return [
            [
                'id'              => 1,
                'code'            => 'INR',
                'name'            => 'Indian Rupee',
                'symbol'          => '₹',
                'html_entity'     => '&#8377;',
                'exchange_rate'   => 1.000000,
                'is_base'         => 1,
                'is_enabled'      => 1,
                'decimal_places'  => 2,
                'symbol_position' => 'before',
                'sort_order'      => 1
            ],
            [
                'id'              => 2,
                'code'            => 'USD',
                'name'            => 'US Dollar',
                'symbol'          => '$',
                'html_entity'     => '&#36;',
                'exchange_rate'   => 0.012000,
                'is_base'         => 0,
                'is_enabled'      => 1,
                'decimal_places'  => 2,
                'symbol_position' => 'before',
                'sort_order'      => 2
            ],
            [
                'id'              => 3,
                'code'            => 'EUR',
                'name'            => 'Euro',
                'symbol'          => '€',
                'html_entity'     => '&euro;',
                'exchange_rate'   => 0.011000,
                'is_base'         => 0,
                'is_enabled'      => 1,
                'decimal_places'  => 2,
                'symbol_position' => 'before',
                'sort_order'      => 3
            ],
            [
                'id'              => 4,
                'code'            => 'GBP',
                'name'            => 'British Pound',
                'symbol'          => '£',
                'html_entity'     => '&pound;',
                'exchange_rate'   => 0.009400,
                'is_base'         => 0,
                'is_enabled'      => 1,
                'decimal_places'  => 2,
                'symbol_position' => 'before',
                'sort_order'      => 4
            ],
            [
                'id'              => 5,
                'code'            => 'AED',
                'name'            => 'UAE Dirham',
                'symbol'          => 'AED',
                'html_entity'     => 'AED',
                'exchange_rate'   => 0.044000,
                'is_base'         => 0,
                'is_enabled'      => 1,
                'decimal_places'  => 2,
                'symbol_position' => 'before',
                'sort_order'      => 5
            ],
            [
                'id'              => 6,
                'code'            => 'CAD',
                'name'            => 'Canadian Dollar',
                'symbol'          => 'CA$',
                'html_entity'     => 'CA&#36;',
                'exchange_rate'   => 0.016300,
                'is_base'         => 0,
                'is_enabled'      => 1,
                'decimal_places'  => 2,
                'symbol_position' => 'before',
                'sort_order'      => 6
            ],
            [
                'id'              => 7,
                'code'            => 'AUD',
                'name'            => 'Australian Dollar',
                'symbol'          => 'AU$',
                'html_entity'     => 'AU&#36;',
                'exchange_rate'   => 0.018200,
                'is_base'         => 0,
                'is_enabled'      => 1,
                'decimal_places'  => 2,
                'symbol_position' => 'before',
                'sort_order'      => 7
            ]
        ];
    }

    /**
     * Auto-initialize table in MySQL if missing (Self-healing on cPanel)
     */
    public static function initTable(): void {
        try {
            $db = Database::connect();
            if (!$db) return;

            $check = $db->query("SHOW TABLES LIKE 'currencies'");
            if ($check && $check->rowCount() > 0) {
                return;
            }

            $sql = "CREATE TABLE IF NOT EXISTS `currencies` (
                `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                `code` VARCHAR(10) NOT NULL UNIQUE,
                `name` VARCHAR(60) NOT NULL,
                `symbol` VARCHAR(20) NOT NULL,
                `html_entity` VARCHAR(30) DEFAULT NULL,
                `exchange_rate` DECIMAL(12, 6) NOT NULL DEFAULT 1.000000,
                `is_base` TINYINT(1) NOT NULL DEFAULT 0,
                `is_enabled` TINYINT(1) NOT NULL DEFAULT 1,
                `decimal_places` TINYINT NOT NULL DEFAULT 2,
                `symbol_position` ENUM('before', 'after') NOT NULL DEFAULT 'before',
                `sort_order` INT NOT NULL DEFAULT 0,
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
            $db->exec($sql);

            $stmt = $db->prepare("INSERT IGNORE INTO `currencies` 
                (`id`, `code`, `name`, `symbol`, `html_entity`, `exchange_rate`, `is_base`, `is_enabled`, `decimal_places`, `symbol_position`, `sort_order`) 
                VALUES (:id, :code, :name, :symbol, :html_entity, :exchange_rate, :is_base, :is_enabled, :decimal_places, :symbol_position, :sort_order)");

            foreach (self::getDefaults() as $d) {
                $stmt->execute($d);
            }
        } catch (Throwable $e) {
            error_log('Currency initTable notice: ' . $e->getMessage());
        }
    }

    /**
     * Clean and sanitize currency symbol (prevents corrupted numbers like 262145)
     */
    public static function cleanSymbol(string $code, ?string $symbol): string {
        $code = strtoupper(trim($code));
        $symbol = trim((string)$symbol);

        // If symbol is empty or is a numeric string (e.g. autofilled pincode 262145), restore proper symbol
        if ($symbol === '' || is_numeric($symbol)) {
            return match ($code) {
                'INR'   => '₹',
                'USD'   => '$',
                'EUR'   => '€',
                'GBP'   => '£',
                'AED'   => 'AED',
                'CAD'   => 'CA$',
                'AUD'   => 'AU$',
                'JPY'   => '¥',
                'SGD'   => 'SG$',
                default => $code
            };
        }

        return $symbol;
    }

    /**
     * Retrieve all currencies (Admin view & Cache)
     */
    public static function getAll(): array {
        if (self::$cachedAll !== null) {
            return self::$cachedAll;
        }

        self::initTable();

        try {
            $db = Database::connect();
            if ($db) {
                $stmt = $db->query("SELECT * FROM `currencies` ORDER BY `sort_order` ASC, `id` ASC");
                if ($stmt) {
                    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    if (!empty($rows)) {
                        foreach ($rows as &$r) {
                            $r['symbol'] = self::cleanSymbol($r['code'], $r['symbol']);
                            $r['exchange_rate'] = (float)$r['exchange_rate'];
                            $r['is_base'] = (int)$r['is_base'];
                            $r['is_enabled'] = (int)$r['is_enabled'];
                            $r['decimal_places'] = (int)$r['decimal_places'];
                        }
                        self::$cachedAll = $rows;
                        return $rows;
                    }
                }
            }
        } catch (Throwable $e) {
            // DB error fallback to static defaults
        }

        self::$cachedAll = self::getDefaults();
        return self::$cachedAll;
    }

    /**
     * Retrieve all active/enabled currencies for storefront visitors
     */
    public static function getEnabled(): array {
        $all = self::getAll();
        return array_values(array_filter($all, fn($c) => !empty($c['is_enabled'])));
    }

    /**
     * Get Base Currency (INR)
     */
    public static function getBase(): array {
        $all = self::getAll();
        foreach ($all as $c) {
            if (!empty($c['is_base'])) {
                return $c;
            }
        }
        return self::getDefaults()[0]; // INR
    }

    /**
     * Get Currency by Code
     */
    public static function findByCode(string $code): ?array {
        $code = strtoupper(trim($code));
        $all = self::getAll();
        foreach ($all as $c) {
            if (strtoupper($c['code']) === $code) {
                return $c;
            }
        }
        return null;
    }

    /**
     * Get Active User Selected Currency (From Session or Cookie, default to Base INR)
     */
    public static function getActive(): array {
        if (self::$activeCurrency !== null) {
            return self::$activeCurrency;
        }

        $code = $_SESSION['selected_currency'] ?? $_COOKIE['selected_currency'] ?? null;
        if (!empty($code) && is_string($code)) {
            $found = self::findByCode($code);
            if ($found && !empty($found['is_enabled'])) {
                self::$activeCurrency = $found;
                return $found;
            }
        }

        self::$activeCurrency = self::getBase();
        return self::$activeCurrency;
    }

    /**
     * Switch Active Currency for Current Visitor
     */
    public static function switchCurrency(string $code): bool {
        $code = strtoupper(trim($code));
        $currency = self::findByCode($code);

        if (!$currency || empty($currency['is_enabled'])) {
            return false;
        }

        $_SESSION['selected_currency'] = $code;
        if (!headers_sent()) {
            setcookie('selected_currency', $code, [
                'expires'  => time() + (86400 * 30),
                'path'     => '/',
                'samesite' => 'Lax',
                'secure'   => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on',
                'httponly' => false
            ]);
        }

        self::$activeCurrency = $currency;
        return true;
    }

    /**
     * Convert an amount in Base Currency (INR) to Target Currency
     */
    public static function convert(float $baseAmountInInr, ?string $targetCode = null): float {
        if ($targetCode !== null) {
            $currency = self::findByCode($targetCode);
        } else {
            $currency = self::getActive();
        }

        if (!$currency) {
            return $baseAmountInInr;
        }

        $rate = (float)($currency['exchange_rate'] ?? 1.0);
        return $baseAmountInInr * $rate;
    }

    /**
     * Format Price with Clean Currency Symbol, Converted Amount & Decimals
     */
    public static function format(mixed $amountInInr = 0.0, ?string $currencyCode = null): string {
        $num = (float)($amountInInr ?? 0.0);
        $curr = $currencyCode ? self::findByCode($currencyCode) : self::getActive();

        if (!$curr) {
            $curr = self::getBase();
        }

        $rate = (float)($curr['exchange_rate'] ?? 1.0);
        $converted = $num * $rate;
        $decimals = (int)($curr['decimal_places'] ?? 2);
        $formattedNum = number_format($converted, $decimals);
        $symbol = self::cleanSymbol($curr['code'], $curr['symbol'] ?? '₹');
        $pos = $curr['symbol_position'] ?? 'before';

        if ($pos === 'after') {
            return $formattedNum . ' ' . $symbol;
        }

        return $symbol . ' ' . $formattedNum;
    }

    /**
     * Get Current Active Currency Symbol
     */
    public static function symbol(): string {
        $curr = self::getActive();
        return self::cleanSymbol($curr['code'], $curr['symbol'] ?? '₹');
    }

    /**
     * Get Current Active Currency Code
     */
    public static function code(): string {
        return self::getActive()['code'] ?? 'INR';
    }

    /**
     * Admin: Save (Insert or Update) Currency
     */
    public static function saveCurrency(array $data): bool {
        self::initTable();

        $code = strtoupper(trim((string)($data['code'] ?? '')));
        $name = trim((string)($data['name'] ?? ''));
        $symbol = self::cleanSymbol($code, (string)($data['symbol'] ?? ''));
        $htmlEntity = trim((string)($data['html_entity'] ?? ''));
        $rate = max(0.000001, (float)($data['exchange_rate'] ?? 1.0));
        $isEnabled = !empty($data['is_enabled']) ? 1 : 0;
        $decimals = max(0, min(4, (int)($data['decimal_places'] ?? 2)));
        $position = ($data['symbol_position'] ?? 'before') === 'after' ? 'after' : 'before';
        $sortOrder = (int)($data['sort_order'] ?? 0);
        $id = !empty($data['id']) ? (int)$data['id'] : null;

        if ($code === '' || $name === '') {
            return false;
        }

        try {
            $db = Database::connect();
            if (!$db) return false;

            if ($id) {
                $stmt = $db->prepare("UPDATE `currencies` SET 
                    `code` = :code,
                    `name` = :name,
                    `symbol` = :symbol,
                    `html_entity` = :html_entity,
                    `exchange_rate` = :exchange_rate,
                    `is_enabled` = :is_enabled,
                    `decimal_places` = :decimal_places,
                    `symbol_position` = :symbol_position,
                    `sort_order` = :sort_order
                    WHERE `id` = :id");
                $result = $stmt->execute([
                    'code'            => $code,
                    'name'            => $name,
                    'symbol'          => $symbol,
                    'html_entity'     => $htmlEntity,
                    'exchange_rate'   => $rate,
                    'is_enabled'      => $isEnabled,
                    'decimal_places'  => $decimals,
                    'symbol_position' => $position,
                    'sort_order'      => $sortOrder,
                    'id'              => $id
                ]);
            } else {
                $stmt = $db->prepare("INSERT INTO `currencies` 
                    (`code`, `name`, `symbol`, `html_entity`, `exchange_rate`, `is_base`, `is_enabled`, `decimal_places`, `symbol_position`, `sort_order`) 
                    VALUES (:code, :name, :symbol, :html_entity, :exchange_rate, 0, :is_enabled, :decimal_places, :symbol_position, :sort_order)");
                $result = $stmt->execute([
                    'code'            => $code,
                    'name'            => $name,
                    'symbol'          => $symbol,
                    'html_entity'     => $htmlEntity,
                    'exchange_rate'   => $rate,
                    'is_enabled'      => $isEnabled,
                    'decimal_places'  => $decimals,
                    'symbol_position' => $position,
                    'sort_order'      => $sortOrder
                ]);
            }

            self::$cachedAll = null;
            self::$activeCurrency = null;
            return $result;
        } catch (Throwable $e) {
            error_log('Currency save error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Admin: Set Base Currency
     */
    public static function setBase(int $id): bool {
        try {
            $db = Database::connect();
            if (!$db) return false;

            $db->beginTransaction();
            $db->exec("UPDATE `currencies` SET `is_base` = 0");
            $stmt = $db->prepare("UPDATE `currencies` SET `is_base` = 1, `exchange_rate` = 1.000000, `is_enabled` = 1 WHERE `id` = :id");
            $stmt->execute(['id' => $id]);
            $db->commit();

            self::$cachedAll = null;
            self::$activeCurrency = null;
            return true;
        } catch (Throwable $e) {
            if (isset($db) && $db->inTransaction()) {
                $db->rollBack();
            }
            return false;
        }
    }

    /**
     * Admin: Toggle Currency Enabled Status
     */
    public static function toggle(int $id): bool {
        try {
            $db = Database::connect();
            if (!$db) return false;

            $stmt = $db->prepare("UPDATE `currencies` SET `is_enabled` = NOT `is_enabled` WHERE `id` = :id AND `is_base` = 0");
            $res = $stmt->execute(['id' => $id]);

            self::$cachedAll = null;
            self::$activeCurrency = null;
            return $res;
        } catch (Throwable $e) {
            return false;
        }
    }

    /**
     * Admin: Delete Currency (Cannot delete base currency)
     */
    public static function deleteCurrency(int $id): bool {
        try {
            $db = Database::connect();
            if (!$db) return false;

            $stmt = $db->prepare("DELETE FROM `currencies` WHERE `id` = :id AND `is_base` = 0");
            $res = $stmt->execute(['id' => $id]);

            self::$cachedAll = null;
            self::$activeCurrency = null;
            return $res;
        } catch (Throwable $e) {
            return false;
        }
    }
}
