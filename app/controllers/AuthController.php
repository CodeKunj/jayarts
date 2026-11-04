<?php
/**
 * JayArt Customer Authentication Controller
 * Login, Registration, Logout
 */

declare(strict_types=1);

class AuthController extends Controller {
    public function login(): void {
        if (Auth::check()) {
            $this->redirect('/profile');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCSRF();

            $email = trim((string)($_POST['email'] ?? ''));
            $password = (string)($_POST['password'] ?? '');

            $userModel = new User();
            $user = $userModel->findByEmail($email);

            if ($user && Auth::verifyPassword($password, $user['password'])) {
                if ($user['status'] !== 'active') {
                    $error = 'Your account has been deactivated. Please contact support.';
                } else {
                    Auth::login($user);
                    Auth::setFlash('success', 'Welcome back, ' . htmlspecialchars($user['name']) . '!');
                    $this->redirect('/profile');
                    return;
                }
            } else {
                $error = 'Invalid email address or password.';
            }
        }

        $this->render('auth/login', [
            'error' => $error ?? null,
            'seo'   => ['title' => 'Sign In to Collector Portal | JayArt Gallery']
        ]);
    }

    public function register(): void {
        if (Auth::check()) {
            $this->redirect('/profile');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCSRF();

            $name = trim((string)($_POST['name'] ?? ''));
            $email = trim((string)($_POST['email'] ?? ''));
            $phone = trim((string)($_POST['phone'] ?? ''));
            $password = (string)($_POST['password'] ?? '');

            $errors = Validator::validate([
                'name'     => $name,
                'email'    => $email,
                'password' => $password,
            ], [
                'name'     => 'required|min:2',
                'email'    => 'required|email',
                'password' => 'required|min:6',
            ]);

            $userModel = new User();
            if (empty($errors)) {
                if ($userModel->findByEmail($email)) {
                    $errors['email'] = 'An account with this email address already exists.';
                } else {
                    $userId = $userModel->createCustomer($name, $email, $password, $phone);
                    $newUser = $userModel->find($userId);
                    Auth::login($newUser);

                    // Send Welcome Email
                    Mailer::send(
                        $email,
                        "Welcome to JayArt Collector Salon",
                        "<h3>Welcome to JayArt, {$name}!</h3><p>Your collector account is active. You can now save paintings to your wishlist, track shipments, and request custom artist commissions.</p>",
                        $name
                    );

                    Auth::setFlash('success', 'Account created successfully!');
                    $this->redirect('/profile');
                    return;
                }
            }
        }

        $this->render('auth/register', [
            'errors' => $errors ?? [],
            'old'    => $_POST ?? [],
            'seo'    => ['title' => 'Join JayArt Collector Salon | JayArt']
        ]);
    }

    public function logout(): void {
        Auth::logout();
        $this->redirect('/');
    }
}

