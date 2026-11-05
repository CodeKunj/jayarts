<?php
/**
 * JayArt Public Currency Controller
 * Allows visitors to seamlessly switch currency from UI header
 */

declare(strict_types=1);

class CurrencyController extends Controller {
    /**
     * Switch currency via GET link: /currency/switch/{code}
     */
    public function switchGet(string $code): void {
        Currency::switchCurrency($code);

        $referer = $_SERVER['HTTP_REFERER'] ?? SITE_URL;
        // Prevent open redirect outside current host
        $host = parse_url($referer, PHP_URL_HOST);
        $currentHost = parse_url(SITE_URL, PHP_URL_HOST);
        if ($host && $currentHost && $host !== $currentHost) {
            $referer = SITE_URL;
        }

        header('Location: ' . $referer);
        exit;
    }

    /**
     * Switch currency via AJAX POST: /currency/switch
     */
    public function switch(): void {
        $code = trim((string)($_POST['code'] ?? ''));
        $success = Currency::switchCurrency($code);

        if ($this->isAjax()) {
            $this->json([
                'success'  => $success,
                'currency' => Currency::getActive()
            ]);
            return;
        }

        $referer = $_SERVER['HTTP_REFERER'] ?? SITE_URL;
        header('Location: ' . $referer);
        exit;
    }

    private function isAjax(): bool {
        return (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')
            || (isset($_SERVER['CONTENT_TYPE']) && str_contains($_SERVER['CONTENT_TYPE'], 'application/json'));
    }
}
