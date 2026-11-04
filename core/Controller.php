<?php
/**
 * JayArt Base Controller
 * View rendering, layouts, JSON output, and redirects
 */

declare(strict_types=1);

abstract class Controller {
    /**
     * Render Public View with Layout
     */
    protected function render(string $view, array $data = [], ?string $layout = 'layouts/main'): void {
        // Extract data variables into local scope for the view
        extract($data);

        $viewPath = APP_PATH . '/views/' . ltrim($view, '/') . '.php';

        if (!file_exists($viewPath)) {
            throw new RuntimeException("View file not found: {$viewPath}");
        }

        // Start output buffering
        ob_start();
        require $viewPath;
        $content = ob_get_clean();

        // If layout specified, wrap content with layout
        if ($layout !== null) {
            $layoutPath = APP_PATH . '/views/' . ltrim($layout, '/') . '.php';
            if (file_exists($layoutPath)) {
                require $layoutPath;
                return;
            }
        }

        // Output raw content if no layout or layout not found
        echo $content;
    }

    /**
     * Render Admin View with Admin Layout
     */
    protected function renderAdmin(string $view, array $data = []): void {
        extract($data);

        $viewPath = ADMIN_PATH . '/views/' . ltrim($view, '/') . '.php';

        if (!file_exists($viewPath)) {
            throw new RuntimeException("Admin view file not found: {$viewPath}");
        }

        ob_start();
        require $viewPath;
        $content = ob_get_clean();

        $adminLayout = ADMIN_PATH . '/views/layouts/main.php';
        if (file_exists($adminLayout)) {
            require $adminLayout;
        } else {
            echo $content;
        }
    }

    /**
     * Output Clean JSON Response (for AJAX, Cart updates, dynamic filters)
     */
    protected function json(mixed $data, int $statusCode = 200): void {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }

    /**
     * Redirect to another URL
     */
    protected function redirect(string $url): void {
        if (!str_starts_with($url, 'http://') && !str_starts_with($url, 'https://')) {
            $url = SITE_URL . '/' . ltrim($url, '/');
        }
        header("Location: {$url}");
        exit;
    }

    /**
     * Verify CSRF Token on POST requests
     */
    protected function validateCSRF(): void {
        $token = $_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? null;
        if (!Validator::verifyCSRF($token)) {
            if ($this->isAjax()) {
                $this->json(['success' => false, 'message' => 'Invalid or expired session security token.'], 403);
            }
            die('Security token mismatch. Please refresh and try again.');
        }
    }

    /**
     * Check if request is AJAX
     */
    protected function isAjax(): bool {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
}

