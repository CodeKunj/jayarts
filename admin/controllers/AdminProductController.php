<?php
/**
 * JayArt Admin Product (Painting) Controller
 * Artwork CRUD + Per-Product Dedicated SEO Engine
 */

declare(strict_types=1);

class AdminProductController extends Controller {
    public function __construct() {
        Auth::requireAdmin();
    }

    public function index(): void {
        $productModel = new Product();
        $paintings = $productModel->fetchAll(
            "SELECT p.*, c.name as category_name,
                    (SELECT image_path FROM `product_images` WHERE `product_id` = p.id ORDER BY `is_primary` DESC LIMIT 1) as primary_image
             FROM `products` p
             LEFT JOIN `categories` c ON p.category_id = c.id
             ORDER BY p.id DESC"
        );

        $this->renderAdmin('products/index', [
            'paintings' => $paintings,
            'title'     => 'Paintings Catalog — JayArt Admin'
        ]);
    }

    public function create(): void {
        $categoryModel = new Category();
        $categories = $categoryModel->all("name ASC");

        $this->renderAdmin('products/form', [
            'product'    => null,
            'categories' => $categories,
            'seo'        => null,
            'isEdit'     => false,
            'title'      => 'Add New Painting — JayArt Admin'
        ]);
    }

    public function store(): void {
        $productModel = new Product();
        
        $title = trim((string)($_POST['title'] ?? ''));
        $slug = Validator::slugify($title);
        // Ensure slug uniqueness
        if ($productModel->findBySlug($slug)) {
            $slug .= '-' . time();
        }

        $price = (float)($_POST['price'] ?? 0);
        $discountPrice = !empty($_POST['discount_price']) ? (float)$_POST['discount_price'] : null;

        $productId = $productModel->insert([
            'category_id'              => !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null,
            'title'                    => $title,
            'slug'                     => $slug,
            'artist_name'              => trim((string)($_POST['artist_name'] ?? 'Independent Master Artist')),
            'medium'                   => trim((string)($_POST['medium'] ?? 'Oil on Canvas')),
            'dimensions'               => trim((string)($_POST['dimensions'] ?? '24 x 36 inches')),
            'year_created'             => trim((string)($_POST['year_created'] ?? date('Y'))),
            'frame_options'            => trim((string)($_POST['frame_options'] ?? 'Rolled Canvas,Stretched Canvas,Floating Matte Black Frame')),
            'price'                    => $price,
            'discount_price'           => $discountPrice,
            'stock_quantity'           => max(0, (int)($_POST['stock_quantity'] ?? 1)),
            'is_original'              => !empty($_POST['is_original']) ? 1 : 0,
            'is_featured'              => !empty($_POST['is_featured']) ? 1 : 0,
            'certificate_authenticity' => !empty($_POST['certificate_authenticity']) ? 1 : 1,
            'description'              => trim((string)($_POST['description'] ?? '')),
            'status'                   => $_POST['status'] ?? 'active',
        ]);

        // Handle Image Upload
        if (!empty($_FILES['artwork_image']['name'])) {
            $ext = strtolower(pathinfo($_FILES['artwork_image']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'], true)) {
                $filename = 'art_' . $productId . '_' . time() . '.' . $ext;
                $dest = UPLOADS_PATH . '/products/' . $filename;
                if (move_uploaded_file($_FILES['artwork_image']['tmp_name'], $dest)) {
                    $db = Database::connect();
                    $db->prepare("INSERT INTO `product_images` (`product_id`, `image_path`, `is_primary`) VALUES (:pid, :path, 1)")
                       ->execute(['pid' => $productId, 'path' => 'uploads/products/' . $filename]);
                }
            }
        }

        // Save Dedicated Per-Product SEO
        $metaTitle = trim((string)($_POST['meta_title'] ?? ''));
        $metaDesc  = trim((string)($_POST['meta_description'] ?? ''));
        $keywords  = trim((string)($_POST['keywords'] ?? ''));
        $canonical = trim((string)($_POST['canonical_url'] ?? SITE_URL . '/product/' . $slug));

        $db = Database::connect();
        $db->prepare("INSERT INTO `product_seo` (`product_id`, `meta_title`, `meta_description`, `keywords`, `canonical_url`) VALUES (:pid, :title, :desc, :kw, :canon)")
           ->execute([
               'pid'   => $productId,
               'title' => $metaTitle ?: ($title . ' | JayArt Gallery'),
               'desc'  => $metaDesc ?: substr(strip_tags((string)$_POST['description']), 0, 155),
               'kw'    => $keywords,
               'canon' => $canonical
           ]);

        // Automatic Redis-like Cache Invalidation
        Cache::deletePattern('products:*');
        Cache::deletePattern('categories:*');
        Cache::deletePattern('home:*');

        Auth::setFlash('success', 'Painting added to gallery catalog with individual SEO optimization.');
        header('Location: ' . ADMIN_URL . '/products');
        exit;
    }

    public function edit(int $id): void {
        $productModel = new Product();
        $product = $productModel->find($id);

        if (!$product) {
            header('Location: ' . ADMIN_URL . '/products');
            exit;
        }

        $categoryModel = new Category();
        $categories = $categoryModel->all("name ASC");

        $db = Database::connect();
        $stmt = $db->prepare("SELECT * FROM `product_seo` WHERE `product_id` = :pid LIMIT 1");
        $stmt->execute(['pid' => $id]);
        $seo = $stmt->fetch() ?: null;

        $images = $productModel->getImages($id);

        $this->renderAdmin('products/form', [
            'product'    => $product,
            'categories' => $categories,
            'seo'        => $seo,
            'images'     => $images,
            'isEdit'     => true,
            'title'      => 'Edit Artwork — ' . $product['title']
        ]);
    }

    public function update(int $id): void {
        $productModel = new Product();
        $product = $productModel->find($id);

        if (!$product) {
            header('Location: ' . ADMIN_URL . '/products');
            exit;
        }

        $title = trim((string)($_POST['title'] ?? ''));
        $price = (float)($_POST['price'] ?? 0);
        $discountPrice = !empty($_POST['discount_price']) ? (float)$_POST['discount_price'] : null;

        $productModel->update($id, [
            'category_id'              => !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null,
            'title'                    => $title,
            'artist_name'              => trim((string)($_POST['artist_name'] ?? 'Independent Master Artist')),
            'medium'                   => trim((string)($_POST['medium'] ?? 'Oil on Canvas')),
            'dimensions'               => trim((string)($_POST['dimensions'] ?? '24 x 36 inches')),
            'year_created'             => trim((string)($_POST['year_created'] ?? '2026')),
            'frame_options'            => trim((string)($_POST['frame_options'] ?? '')),
            'price'                    => $price,
            'discount_price'           => $discountPrice,
            'stock_quantity'           => max(0, (int)($_POST['stock_quantity'] ?? 1)),
            'is_original'              => !empty($_POST['is_original']) ? 1 : 0,
            'is_featured'              => !empty($_POST['is_featured']) ? 1 : 0,
            'certificate_authenticity' => !empty($_POST['certificate_authenticity']) ? 1 : 0,
            'description'              => trim((string)($_POST['description'] ?? '')),
            'status'                   => $_POST['status'] ?? 'active',
        ]);

        // Upload image if provided
        if (!empty($_FILES['artwork_image']['name'])) {
            $ext = strtolower(pathinfo($_FILES['artwork_image']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'], true)) {
                $filename = 'art_' . $id . '_' . time() . '.' . $ext;
                $dest = UPLOADS_PATH . '/products/' . $filename;
                if (move_uploaded_file($_FILES['artwork_image']['tmp_name'], $dest)) {
                    $db = Database::connect();
                    $db->prepare("INSERT INTO `product_images` (`product_id`, `image_path`, `is_primary`) VALUES (:pid, :path, 1)")
                       ->execute(['pid' => $id, 'path' => 'uploads/products/' . $filename]);
                }
            }
        }

        // Update Per-Product SEO
        $metaTitle = trim((string)($_POST['meta_title'] ?? ''));
        $metaDesc  = trim((string)($_POST['meta_description'] ?? ''));
        $keywords  = trim((string)($_POST['keywords'] ?? ''));
        $canonical = trim((string)($_POST['canonical_url'] ?? SITE_URL . '/product/' . $product['slug']));

        $db = Database::connect();
        $db->prepare("INSERT INTO `product_seo` (`product_id`, `meta_title`, `meta_description`, `keywords`, `canonical_url`) 
                      VALUES (:pid, :title, :desc, :kw, :canon) 
                      ON DUPLICATE KEY UPDATE `meta_title` = :title2, `meta_description` = :desc2, `keywords` = :kw2, `canonical_url` = :canon2")
           ->execute([
               'pid'    => $id,
               'title'  => $metaTitle,
               'desc'   => $metaDesc,
               'kw'     => $keywords,
               'canon'  => $canonical,
               'title2' => $metaTitle,
               'desc2'  => $metaDesc,
               'kw2'    => $keywords,
               'canon2' => $canonical,
           ]);

        // Automatic Redis-like Cache Invalidation
        Cache::deletePattern('products:*');
        Cache::deletePattern('categories:*');
        Cache::deletePattern('home:*');

        Auth::setFlash('success', 'Artwork and individual SEO metadata saved successfully.');
        header('Location: ' . ADMIN_URL . '/products');
        exit;
    }

    public function delete(int $id): void {
        $productModel = new Product();
        $productModel->delete($id);

        // Automatic Redis-like Cache Invalidation
        Cache::deletePattern('products:*');
        Cache::deletePattern('categories:*');
        Cache::deletePattern('home:*');

        Auth::setFlash('success', 'Artwork removed from catalog.');
        header('Location: ' . ADMIN_URL . '/products');
        exit;
    }
}

