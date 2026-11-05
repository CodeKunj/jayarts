<?php
/**
 * JayArt Admin Multi-Currency & FX Rates Controller
 */

declare(strict_types=1);

class AdminCurrencyController extends Controller {
    public function __construct() {
        Auth::requireAdmin();
    }

    /**
     * List all currencies & exchange rate management
     */
    public function index(): void {
        $currencies = Currency::getAll();
        $baseCurrency = Currency::getBase();

        $this->renderAdmin('currencies/index', [
            'currencies'   => $currencies,
            'baseCurrency' => $baseCurrency,
            'title'        => 'Currencies & FX Exchange Rates — JayArt Admin'
        ]);
    }

    /**
     * Create new currency
     */
    public function store(): void {
        Validator::validateCsrf();

        $code = strtoupper(trim((string)($_POST['code'] ?? '')));
        $name = trim((string)($_POST['name'] ?? ''));

        if ($code === '' || $name === '') {
            Auth::setFlash('error', 'Currency code and name are required.');
            header('Location: ' . ADMIN_URL . '/currencies');
            exit;
        }

        $res = Currency::saveCurrency($_POST);

        if ($res) {
            Auth::setFlash('success', "Currency {$code} added successfully.");
        } else {
            Auth::setFlash('error', "Could not save currency {$code}. Please check input values.");
        }

        header('Location: ' . ADMIN_URL . '/currencies');
        exit;
    }

    /**
     * Update existing currency
     */
    public function update(string $id): void {
        Validator::validateCsrf();

        $_POST['id'] = (int)$id;
        $res = Currency::saveCurrency($_POST);

        if ($res) {
            Auth::setFlash('success', 'Currency settings and exchange rate updated.');
        } else {
            Auth::setFlash('error', 'Failed to update currency.');
        }

        header('Location: ' . ADMIN_URL . '/currencies');
        exit;
    }

    /**
     * Toggle enabled/disabled status
     */
    public function toggle(string $id): void {
        Validator::validateCsrf();

        Currency::toggle((int)$id);
        Auth::setFlash('success', 'Currency status updated.');
        header('Location: ' . ADMIN_URL . '/currencies');
        exit;
    }

    /**
     * Set a currency as the Base Currency
     */
    public function setBase(string $id): void {
        Validator::validateCsrf();

        Currency::setBase((int)$id);
        Auth::setFlash('success', 'Base currency updated successfully.');
        header('Location: ' . ADMIN_URL . '/currencies');
        exit;
    }

    /**
     * Delete currency
     */
    public function delete(string $id): void {
        Validator::validateCsrf();

        $res = Currency::deleteCurrency((int)$id);
        if ($res) {
            Auth::setFlash('success', 'Currency removed successfully.');
        } else {
            Auth::setFlash('error', 'Cannot delete base currency.');
        }

        header('Location: ' . ADMIN_URL . '/currencies');
        exit;
    }
}
