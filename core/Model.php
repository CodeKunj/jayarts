<?php
/**
 * JayArt Base Model
 * Secure PDO wrapper with prepared statements
 */

declare(strict_types=1);

abstract class Model {
    protected ?PDO $db = null;
    protected string $table = '';
    protected string $primaryKey = 'id';

    public function __construct() {
        $this->db = Database::connect();
    }

    /**
     * Execute SQL Query with Prepared Statement
     */
    public function query(string $sql, array $params = []): ?PDOStatement {
        if ($this->db === null) {
            return null;
        }

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            error_log("Database Query Error [{$sql}]: " . $e->getMessage());

            // If table does not exist (1146 / 42S02), trigger auto-migration and retry once
            if ($e->getCode() === '42S02' || str_contains($e->getMessage(), "doesn't exist")) {
                Database::autoMigrateIfEmpty($this->db);
                try {
                    $stmt = $this->db->prepare($sql);
                    $stmt->execute($params);
                    return $stmt;
                } catch (Throwable $retryEx) {
                    error_log("Query retry failed after autoMigrate: " . $retryEx->getMessage());
                }
            }

            return null;
        }
    }

    /**
     * Fetch All Rows
     */
    public function fetchAll(string $sql, array $params = []): array {
        $stmt = $this->query($sql, $params);
        return $stmt ? $stmt->fetchAll() : [];
    }

    /**
     * Fetch Single Row
     */
    public function fetch(string $sql, array $params = []): ?array {
        $stmt = $this->query($sql, $params);
        if (!$stmt) {
            return null;
        }
        $result = $stmt->fetch();
        return $result ?: null;
    }

    /**
     * Find by Primary Key
     */
    public function find(int|string $id): ?array {
        $sql = "SELECT * FROM `{$this->table}` WHERE `{$this->primaryKey}` = :id LIMIT 1";
        return $this->fetch($sql, ['id' => $id]);
    }

    /**
     * Find by Single Column
     */
    public function findBy(string $column, mixed $value): ?array {
        $sql = "SELECT * FROM `{$this->table}` WHERE `{$column}` = :val LIMIT 1";
        return $this->fetch($sql, ['val' => $value]);
    }

    /**
     * Fetch All Records in Table
     */
    public function all(string $orderBy = 'id DESC', ?int $limit = null): array {
        $sql = "SELECT * FROM `{$this->table}` ORDER BY {$orderBy}";
        if ($limit !== null) {
            $sql .= " LIMIT " . (int)$limit;
        }
        return $this->fetchAll($sql);
    }

    /**
     * Insert Record
     */
    public function insert(array $data): int {
        if ($this->db === null) {
            return 0;
        }

        $columns = array_keys($data);
        $fields = implode('`, `', $columns);
        $placeholders = ':' . implode(', :', $columns);

        $sql = "INSERT INTO `{$this->table}` (`{$fields}`) VALUES ({$placeholders})";
        $this->query($sql, $data);

        return (int)$this->db->lastInsertId();
    }

    /**
     * Update Record by Primary Key
     */
    public function update(int|string $id, array $data): bool {
        $setClauses = [];
        $params = ['id' => $id];

        foreach ($data as $column => $value) {
            $setClauses[] = "`{$column}` = :{$column}";
            $params[$column] = $value;
        }

        $setSql = implode(', ', $setClauses);
        $sql = "UPDATE `{$this->table}` SET {$setSql} WHERE `{$this->primaryKey}` = :id";

        $stmt = $this->query($sql, $params);
        return $stmt !== null;
    }

    /**
     * Delete Record by Primary Key
     */
    public function delete(int|string $id): bool {
        $sql = "DELETE FROM `{$this->table}` WHERE `{$this->primaryKey}` = :id";
        $stmt = $this->query($sql, ['id' => $id]);
        return $stmt !== null;
    }

    /**
     * Count Records matching optional WHERE clause
     */
    public function count(string $where = '', array $params = []): int {
        $sql = "SELECT COUNT(*) as total FROM `{$this->table}`";
        if (!empty($where)) {
            $sql .= " WHERE {$where}";
        }
        $row = $this->fetch($sql, $params);
        return (int)($row['total'] ?? 0);
    }

    /**
     * Get Last Insert ID
     */
    public function lastInsertId(): int {
        return $this->db ? (int)$this->db->lastInsertId() : 0;
    }
}

