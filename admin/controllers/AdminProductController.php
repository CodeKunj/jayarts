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
            'images'     => [],
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

        // Handle Primary Artwork Image Upload
        $hasPrimary = false;
        if (!empty($_FILES['artwork_image']['name']) && ($_FILES['artwork_image']['error'] ?? 1) === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($_FILES['artwork_image']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'], true)) {
                $filename = 'art_' . $productId . '_' . time() . '.' . $ext;
                $dest = UPLOADS_PATH . '/products/' . $filename;
                if (move_uploaded_file($_FILES['artwork_image']['tmp_name'], $dest)) {
                    $db = Database::connect();
                    $db->prepare("INSERT INTO `product_images` (`product_id`, `image_path`, `is_primary`, `sort_order`) VALUES (:pid, :path, 1, 0)")
                       ->execute(['pid' => $productId, 'path' => 'uploads/products/' . $filename]);
                    $hasPrimary = true;
                }
            }
        }

        // Handle Multiple Additional Gallery Images Upload
        if (!empty($_FILES['gallery_images'])) {
            $this->uploadGalleryImages($productId, $_FILES['gallery_images'], $hasPrimary);
        }

        // Save Dedicated Per-Product SEO
        $metaTitle = trim((string)($_POST['meta_title'] ?? ''));
        $metaDesc  = trim((string)($_POST['meta_description'] ?? ''));
        $keywords  = trim((string)($_POST['keywords'] ?? ''));
        $canonical = trim((string)($_POST['canonical_url'] ?? SITE_URL . '/product/' . $slug));
        $ogImage   = trim((string)($_POST['og_image'] ?? ''));

        // Handle Custom OG Image File Upload
        if (!empty($_FILES['product_og_image_file']['tmp_name']) && is_uploaded_file($_FILES['product_og_image_file']['tmp_name'])) {
            $file = $_FILES['product_og_image_file'];
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'svg'], true)) {
                $ogDir = ROOT_PATH . '/uploads/products/og';
                if (!is_dir($ogDir)) @mkdir($ogDir, 0755, true);
                $ogFilename = 'og-product-' . time() . '-' . uniqid() . '.' . $ext;
                if (move_uploaded_file($file['tmp_name'], $ogDir . '/' . $ogFilename)) {
                    $ogImage = 'uploads/products/og/' . $ogFilename;
                }
            }
        }

        $db = Database::connect();
        $db->prepare("INSERT INTO `product_seo` (`product_id`, `meta_title`, `meta_description`, `keywords`, `canonical_url`, `og_image`) VALUES (:pid, :title, :desc, :kw, :canon, :og)")
           ->execute([
               'pid'   => $productId,
               'title' => $metaTitle ?: ($title . ' | JayArt Gallery'),
               'desc'  => $metaDesc ?: substr(strip_tags((string)$_POST['description']), 0, 155),
               'kw'    => $keywords,
               'canon' => $canonical,
               'og'    => $ogImage !== '' ? $ogImage : null
           ]);

        // Automatic Redis-like Cache Invalidation
        Cache::deletePattern('products:*');
        Cache::deletePattern('categories:*');
        Cache::deletePattern('home:*');

        Auth::setFlash('success', 'Painting added to gallery catalog with individual SEO optimization.');
        header('Location: ' . ADMIN_URL . '/products');
        exit;
    }

    public function edit(int|string $id): void {
        $id = (int)$id;
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

    public function update(int|string $id): void {
        $id = (int)$id;
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

        // Upload primary cover image if provided
        $hasNewPrimary = false;
        if (!empty($_FILES['artwork_image']['name']) && ($_FILES['artwork_image']['error'] ?? 1) === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($_FILES['artwork_image']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'], true)) {
                $filename = 'art_' . $id . '_' . time() . '.' . $ext;
                $dest = UPLOADS_PATH . '/products/' . $filename;
                if (move_uploaded_file($_FILES['artwork_image']['tmp_name'], $dest)) {
                    $db = Database::connect();
                    // Set all prior images for this product to non-primary
                    $db->prepare("UPDATE `product_images` SET `is_primary` = 0 WHERE `product_id` = :pid")->execute(['pid' => $id]);
                    // Insert new primary image
                    $db->prepare("INSERT INTO `product_images` (`product_id`, `image_path`, `is_primary`, `sort_order`) VALUES (:pid, :path, 1, 0)")
                       ->execute(['pid' => $id, 'path' => 'uploads/products/' . $filename]);
                    $hasNewPrimary = true;
                }
            }
        }

        // Handle Multiple Additional Gallery Images Upload during Edit
        if (!empty($_FILES['gallery_images'])) {
            $this->uploadGalleryImages($id, $_FILES['gallery_images'], $hasNewPrimary);
        }

        // Handle Setting Primary Image from Existing Gallery Photos
        if (!empty($_POST['primary_image_id']) && !$hasNewPrimary) {
            $primId = (int)$_POST['primary_image_id'];
            $db = Database::connect();
            $db->prepare("UPDATE `product_images` SET `is_primary` = 0 WHERE `product_id` = :pid")->execute(['pid' => $id]);
            $db->prepare("UPDATE `product_images` SET `is_primary` = 1 WHERE `id` = :id AND `product_id` = :pid")
               ->execute(['id' => $primId, 'pid' => $id]);
        }

        // Handle Deleting Marked Gallery Photos via Checkboxes
        if (!empty($_POST['delete_image_ids']) && is_array($_POST['delete_image_ids'])) {
            foreach ($_POST['delete_image_ids'] as $delId) {
                $this->removeImageFileAndRecord((int)$delId, $id);
            }
        }

        // Update Per-Product SEO
        $metaTitle = trim((string)($_POST['meta_title'] ?? ''));
        $metaDesc  = trim((string)($_POST['meta_description'] ?? ''));
        $keywords  = trim((string)($_POST['keywords'] ?? ''));
        $canonical = trim((string)($_POST['canonical_url'] ?? SITE_URL . '/product/' . $product['slug']));
        $ogImage   = trim((string)($_POST['og_image'] ?? ''));

        // Handle Custom OG Image File Upload
        if (!empty($_FILES['product_og_image_file']['tmp_name']) && is_uploaded_file($_FILES['product_og_image_file']['tmp_name'])) {
            $file = $_FILES['product_og_image_file'];
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'svg'], true)) {
                $ogDir = ROOT_PATH . '/uploads/products/og';
                if (!is_dir($ogDir)) @mkdir($ogDir, 0755, true);
                $ogFilename = 'og-product-' . time() . '-' . uniqid() . '.' . $ext;
                if (move_uploaded_file($file['tmp_name'], $ogDir . '/' . $ogFilename)) {
                    $ogImage = 'uploads/products/og/' . $ogFilename;
                }
            }
        }

        $db = Database::connect();
        $db->prepare("INSERT INTO `product_seo` (`product_id`, `meta_title`, `meta_description`, `keywords`, `canonical_url`, `og_image`) 
                      VALUES (:pid, :title, :desc, :kw, :canon, :og) 
                      ON DUPLICATE KEY UPDATE `meta_title` = :title2, `meta_description` = :desc2, `keywords` = :kw2, `canonical_url` = :canon2, `og_image` = :og2")
           ->execute([
               'pid'    => $id,
               'title'  => $metaTitle,
               'desc'   => $metaDesc,
               'kw'     => $keywords,
               'canon'  => $canonical,
               'og'     => $ogImage !== '' ? $ogImage : null,
               'title2' => $metaTitle,
               'desc2'  => $metaDesc,
               'kw2'    => $keywords,
               'canon2' => $canonical,
               'og2'    => $ogImage !== '' ? $ogImage : null,
           ]);

        // Automatic Redis-like Cache Invalidation
        Cache::deletePattern('products:*');
        Cache::deletePattern('categories:*');
        Cache::deletePattern('home:*');

        Auth::setFlash('success', 'Artwork and individual SEO metadata saved successfully.');
        header('Location: ' . ADMIN_URL . '/products');
        exit;
    }

    public function delete(int|string $id): void {
        $id = (int)$id;
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

    /**
     * Upload Multiple Additional Gallery Images
     */
    private function uploadGalleryImages(int $productId, array $files, bool $hasPrimary = false): int {
        $count = 0;
        if (empty($files['name']) || !is_array($files['name'])) {
            return 0;
        }

        $db = Database::connect();
        $stmtMax = $db->prepare("SELECT MAX(sort_order) as max_sort FROM `product_images` WHERE `product_id` = :pid");
        $stmtMax->execute(['pid' => $productId]);
        $row = $stmtMax->fetch();
        $nextSort = (int)($row['max_sort'] ?? 0) + 1;

        if (!$hasPrimary) {
            $stmtPrimary = $db->prepare("SELECT COUNT(*) as primary_count FROM `product_images` WHERE `product_id` = :pid AND `is_primary` = 1");
            $stmtPrimary->execute(['pid' => $productId]);
            $hasPrimary = ((int)($stmtPrimary->fetch()['primary_count'] ?? 0)) > 0;
        }

        $targetDir = UPLOADS_PATH . '/products';
        if (!is_dir($targetDir)) {
            @mkdir($targetDir, 0755, true);
        }

        $allowedExts = ['jpg', 'jpeg', 'png', 'webp'];

        foreach ($files['name'] as $i => $name) {
            if (empty($name) || ($files['error'][$i] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
                continue;
            }

            $ext = strtolower(pathinfo((string)$name, PATHINFO_EXTENSION));
            if (!in_array($ext, $allowedExts, true)) {
                continue;
            }

            $filename = 'art_' . $productId . '_gal_' . time() . '_' . $i . '_' . bin2hex(random_bytes(3)) . '.' . $ext;
            $dest = $targetDir . '/' . $filename;

            if (move_uploaded_file($files['tmp_name'][$i], $dest)) {
                $isPrimary = (!$hasPrimary && $count === 0) ? 1 : 0;
                if ($isPrimary) $hasPrimary = true;

                $stmt = $db->prepare("INSERT INTO `product_images` (`product_id`, `image_path`, `is_primary`, `sort_order`) VALUES (:pid, :path, :primary, :sort)");
                $stmt->execute([
                    'pid'     => $productId,
                    'path'    => 'uploads/products/' . $filename,
                    'primary' => $isPrimary,
                    'sort'    => $nextSort++
                ]);
                $count++;
            }
        }

        return $count;
    }

    /**
     * Delete an individual gallery photo (supports AJAX or redirect)
     */
    public function deleteImage(int|string $imageId): void {
        $imageId = (int)$imageId;
        $db = Database::connect();

        $stmt = $db->prepare("SELECT * FROM `product_images` WHERE `id` = :id LIMIT 1");
        $stmt->execute(['id' => $imageId]);
        $image = $stmt->fetch();

        if ($image) {
            $productId = (int)$image['product_id'];
            $wasPrimary = (int)$image['is_primary'] === 1;

            $this->removeImageFileAndRecord($imageId, $productId);

            if ($wasPrimary) {
                // Elect another existing photo as primary
                $db->prepare("UPDATE `product_images` SET `is_primary` = 1 WHERE `product_id` = :pid ORDER BY `id` ASC LIMIT 1")
                   ->execute(['pid' => $productId]);
            }

            Cache::deletePattern('products:*');
            Cache::deletePattern('home:*');

            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                header('Content-Type: application/json');
                echo json_encode(['success' => true, 'message' => 'Gallery photo deleted successfully']);
                exit;
            }

            Auth::setFlash('success', 'Gallery photo deleted successfully.');
            header('Location: ' . ADMIN_URL . '/products/edit/' . $productId);
            exit;
        }

        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
            header('Content-Type: application/json');
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Image not found']);
            exit;
        }

        header('Location: ' . ADMIN_URL . '/products');
        exit;
    }

    /**
     * Make an existing gallery photo the primary cover image
     */
    public function setPrimaryImage(int|string $imageId): void {
        $imageId = (int)$imageId;
        $db = Database::connect();

        $stmt = $db->prepare("SELECT * FROM `product_images` WHERE `id` = :id LIMIT 1");
        $stmt->execute(['id' => $imageId]);
        $image = $stmt->fetch();

        if ($image) {
            $productId = (int)$image['product_id'];
            $db->prepare("UPDATE `product_images` SET `is_primary` = 0 WHERE `product_id` = :pid")->execute(['pid' => $productId]);
            $db->prepare("UPDATE `product_images` SET `is_primary` = 1 WHERE `id` = :id AND `product_id` = :pid")
               ->execute(['id' => $imageId, 'pid' => $productId]);

            Cache::deletePattern('products:*');
            Cache::deletePattern('home:*');

            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                header('Content-Type: application/json');
                echo json_encode(['success' => true, 'message' => 'Cover image updated successfully']);
                exit;
            }

            Auth::setFlash('success', 'Primary cover photo updated.');
            header('Location: ' . ADMIN_URL . '/products/edit/' . $productId);
            exit;
        }

        header('Location: ' . ADMIN_URL . '/products');
        exit;
    }

    /**
     * Helper to safely remove image file and DB record
     */
    private function removeImageFileAndRecord(int $imageId, int $productId): void {
        $db = Database::connect();
        $stmt = $db->prepare("SELECT `image_path` FROM `product_images` WHERE `id` = :id AND `product_id` = :pid LIMIT 1");
        $stmt->execute(['id' => $imageId, 'pid' => $productId]);
        $row = $stmt->fetch();

        if ($row && !empty($row['image_path'])) {
            $path = $row['image_path'];
            if (!str_starts_with($path, 'http://') && !str_starts_with($path, 'https://')) {
                $fullPath = ROOT_PATH . '/' . ltrim($path, '/');
                if (file_exists($fullPath) && is_file($fullPath)) {
                    @unlink($fullPath);
                }
            }
        }

        $db->prepare("DELETE FROM `product_images` WHERE `id` = :id AND `product_id` = :pid")
           ->execute(['id' => $imageId, 'pid' => $productId]);
    }
}

