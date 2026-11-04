<?php
/**
 * JayArt Shop / Painting Gallery Controller
 */

declare(strict_types=1);

class ShopController extends Controller {
    public function index(): void {
        $productModel = new Product();
        $categoryModel = new Category();

        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $filters = [
            'category'    => $_GET['category'] ?? null,
            'medium'      => $_GET['medium'] ?? null,
            'search'      => $_GET['search'] ?? null,
            'filter'      => $_GET['filter'] ?? null,
            'min_price'   => $_GET['min_price'] ?? null,
            'max_price'   => $_GET['max_price'] ?? null,
            'is_original' => $_GET['is_original'] ?? null,
            'sort'        => $_GET['sort'] ?? null,
        ];

        $results = $productModel->filter($filters, $page, 12);
        $categories = $categoryModel->getActiveWithCounts();

        // If AJAX request, return partial JSON for instant client-side update
        if ($this->isAjax()) {
            $this->json($results);
            return;
        }

        // Active Category for SEO Title
        $currentCatName = 'Original Paintings & Fine Art Gallery';
        if (!empty($filters['category'])) {
            $activeCat = $categoryModel->findBySlug($filters['category']);
            if ($activeCat) {
                $currentCatName = $activeCat['name'] . ' — JayArt Gallery';
            }
        }

        $this->render('shop/index', [
            'paintings'   => $results['items'],
            'products'    => $results['items'],
            'items'       => $results['items'],
            'total'       => $results['total'],
            'currentPage' => $results['current_page'],
            'totalPages'  => $results['total_pages'],
            'categories'  => $categories,
            'filters'     => $filters,
            'seo'         => [
                'title'       => $currentCatName . ' | JayArt',
                'description' => 'Explore authenticated original paintings, oil on canvas, watercolors, and modern abstract art pieces with certificates of provenance.',
                'keywords'    => 'buy paintings online, original art, oil on canvas, watercolors, abstract art gallery',
                'og_type'     => 'website'
            ]
        ]);
    }
}

