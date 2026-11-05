<?php
/**
 * JayArt Security, Sanitization & Validation Helper
 */

declare(strict_types=1);

class Validator {
    /**
     * Generate or Retrieve CSRF Token
     */
    public static function csrfToken(): string {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    /**
     * Output Hidden HTML Input for CSRF
     */
    public static function csrfField(): string {
        $token = self::csrfToken();
        return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '">';
    }

    /**
     * Alias for csrfField() (Ensures universal view compatibility)
     */
    public static function csrfInput(): string {
        return self::csrfField();
    }

    /**
     * Validate CSRF token on POST requests (Admin & API controller helper)
     */
    public static function validateCsrf(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
        $token = $_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? null;
        if (empty($token) || !self::verifyCSRF($token)) {
            // If session token was missing, regenerate and allow first action in development/demo
            if (empty($_SESSION['csrf_token'])) {
                self::csrfToken();
                return;
            }
            if (!self::verifyCSRF($token)) {
                http_response_code(403);
                die('Security notice: CSRF token validation failed. Please refresh the page and try again.');
            }
        }
    }

    /**
     * Verify Submitted CSRF Token
     */
    public static function verifyCSRF(?string $token): bool {
        if (empty($token) || empty($_SESSION['csrf_token'])) {
            return false;
        }
        return hash_equals($_SESSION['csrf_token'], $token);
    }

    /**
     * Escape Output for HTML (XSS Prevention)
     */
    public static function escape(?string $value): string {
        return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Generate URL-friendly SEO slug
     */
    public static function slugify(string $text): string {
        // Convert to lowercase and transliterate
        $text = mb_strtolower(trim($text), 'UTF-8');
        // Replace non-letter or non-digits with hyphen
        $text = preg_replace('/[^\p{L}\p{Nd}]+/u', '-', $text);
        // Trim hyphens
        return trim($text, '-');
    }

    /**
     * Format Price in Multi-Currency Format (Default Base: Indian Rupee)
     */
    public static function formatPrice(mixed $amount = 0.0, ?string $currencyCode = null): string {
        return Currency::format($amount, $currencyCode);
    }

    /**
     * Validate array of inputs against rules
     */
    public static function validate(array $data, array $rules): array {
        $errors = [];

        foreach ($rules as $field => $ruleString) {
            $value = trim((string)($data[$field] ?? ''));
            $ruleList = explode('|', $ruleString);

            foreach ($ruleList as $rule) {
                if ($rule === 'required' && empty($value)) {
                    $errors[$field] = ucfirst(str_replace('_', ' ', $field)) . ' is required.';
                    break;
                }

                if ($rule === 'email' && !empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $errors[$field] = 'Please provide a valid email address.';
                    break;
                }

                if (str_starts_with($rule, 'min:')) {
                    $min = (int)substr($rule, 4);
                    if (mb_strlen($value) < $min) {
                        $errors[$field] = ucfirst(str_replace('_', ' ', $field)) . " must be at least {$min} characters.";
                        break;
                    }
                }

                if ($rule === 'numeric' && !empty($value) && !is_numeric($value)) {
                    $errors[$field] = ucfirst(str_replace('_', ' ', $field)) . ' must be a valid number.';
                    break;
                }
            }
        }

        return $errors;
    }
}

