<?php
/**
 * JayArt Admin Category Controller (Art Mediums & Styles)
 */

declare(strict_types=1);

class AdminCategoryController extends Controller {
    public function __construct() {
        Auth::requireAdmin();
    }

    public function index(): void {
        $categoryModel = new Category();
        $categories = $categoryModel->getActiveWithCounts();

        $this->renderAdmin('categories/index', [
            'categories' => $categories,
            'title'      => 'Art Mediums & Categories — JayArt Admin'
        ]);
    }

    public function save(): void {
        $categoryModel = new Category();

        $id = !empty($_POST['id']) ? (int)$_POST['id'] : null;
        $name = trim((string)($_POST['name'] ?? ''));
        $slug = Validator::slugify($name);
        $desc = trim((string)($_POST['description'] ?? ''));

        if ($id) {
            $categoryModel->update($id, [
                'name'        => $name,
                'description' => $desc,
            ]);
            Auth::setFlash('success', 'Medium category updated.');
        } else {
            $categoryModel->insert([
                'name'        => $name,
                'slug'        => $slug,
                'description' => $desc,
                'status'      => 'active'
            ]);
            Auth::setFlash('success', 'New art medium created.');
        }

        // Automatic Redis-like Cache Invalidation
        Cache::deletePattern('categories:*');
        Cache::deletePattern('products:*');
        Cache::deletePattern('home:*');

        header('Location: ' . ADMIN_URL . '/categories');
        exit;
    }

    public function delete(int $id): void {
        $categoryModel = new Category();
        $categoryModel->delete($id);

        // Automatic Redis-like Cache Invalidation
        Cache::deletePattern('categories:*');
        Cache::deletePattern('products:*');
        Cache::deletePattern('home:*');

        Auth::setFlash('success', 'Category deleted.');
        header('Location: ' . ADMIN_URL . '/categories');
        exit;
    }
}

