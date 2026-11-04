<?php
/**
 * JayArt Admin Authentication Controller
 */

declare(strict_types=1);

class AdminAuthController extends Controller {
    public function login(): void {
        if (Auth::checkAdmin()) {
            header('Location: ' . ADMIN_URL);
            exit;
        }

        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim((string)($_POST['email'] ?? ''));
            $password = (string)($_POST['password'] ?? '');

            $userModel = new User();
            $admin = $userModel->findByEmail($email);

            if ($admin && in_array($admin['role'], ['admin', 'manager'], true) && Auth::verifyPassword($password, $admin['password'])) {
                if ($admin['status'] !== 'active') {
                    $error = 'Your administrator account is deactivated.';
                } else {
                    Auth::loginAdmin($admin);
                    header('Location: ' . ADMIN_URL);
                    exit;
                }
            } else {
                $error = 'Invalid administrator credentials.';
            }
        }

        // Render standalone admin login (without standard admin sidebar)
        require ADMIN_PATH . '/views/auth/login.php';
    }

    public function logout(): void {
        Auth::logoutAdmin();
        header('Location: ' . ADMIN_URL . '/login');
        exit;
    }
}

