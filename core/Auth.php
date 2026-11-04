<?php
/**
 * JayArt Authentication & Session Manager
 * Role-based access control (Admin, Manager, Customer)
 */

declare(strict_types=1);

class Auth {
    /**
     * Hash Password securely
     */
    public static function hashPassword(string $password): string {
        return password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
    }

    /**
     * Verify Password against hash
     */
    public static function verifyPassword(string $password, string $hash): bool {
        return password_verify($password, $hash);
    }

    /**
     * Check if customer is logged in
     */
    public static function check(): bool {
        return isset($_SESSION['user']) && !empty($_SESSION['user']['id']);
    }

    /**
     * Get current logged-in customer data
     */
    public static function user(): ?array {
        return $_SESSION['user'] ?? null;
    }

    /**
     * Get customer ID
     */
    public static function id(): ?int {
        return isset($_SESSION['user']['id']) ? (int)$_SESSION['user']['id'] : null;
    }

    /**
     * Log customer in
     */
    public static function login(array $user): void {
        session_regenerate_id(true);
        $_SESSION['user'] = [
            'id'    => (int)$user['id'],
            'name'  => $user['name'],
            'email' => $user['email'],
            'role'  => $user['role'] ?? 'customer',
            'phone' => $user['phone'] ?? null,
            'avatar'=> $user['avatar'] ?? null
        ];
    }

    /**
     * Customer Logout
     */
    public static function logout(): void {
        unset($_SESSION['user']);
    }

    /**
     * Check if Admin is logged in
     */
    public static function checkAdmin(): bool {
        return isset($_SESSION['admin']) && 
               in_array($_SESSION['admin']['role'] ?? '', ['admin', 'manager'], true);
    }

    /**
     * Get current logged-in Admin data
     */
    public static function admin(): ?array {
        return $_SESSION['admin'] ?? null;
    }

    /**
     * Log Admin in
     */
    public static function loginAdmin(array $admin): void {
        session_regenerate_id(true);
        $_SESSION['admin'] = [
            'id'    => (int)$admin['id'],
            'name'  => $admin['name'],
            'email' => $admin['email'],
            'role'  => $admin['role'] ?? 'admin'
        ];
    }

    /**
     * Admin Logout
     */
    public static function logoutAdmin(): void {
        unset($_SESSION['admin']);
    }

    /**
     * Protect Admin Route Middleware
     */
    public static function requireAdmin(): void {
        if (!self::checkAdmin()) {
            $_SESSION['flash_error'] = 'Please log in to access the administration panel.';
            header('Location: ' . ADMIN_URL . '/login');
            exit;
        }
    }

    /**
     * Set Session Flash Message
     */
    public static function setFlash(string $type, string $message): void {
        $_SESSION['flash_' . $type] = $message;
    }

    /**
     * Retrieve and clear Flash Message
     */
    public static function getFlash(string $type): ?string {
        $key = 'flash_' . $type;
        if (isset($_SESSION[$key])) {
            $msg = $_SESSION[$key];
            unset($_SESSION[$key]);
            return $msg;
        }
        return null;
    }
}

