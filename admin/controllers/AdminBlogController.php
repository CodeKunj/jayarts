<?php
/**
 * JayArt Admin Blog Controller
 * Articles CRUD + Dedicated Per-Blog SEO Engine
 */

declare(strict_types=1);

class AdminBlogController extends Controller {
    public function __construct() {
        Auth::requireAdmin();
    }

    public function index(): void {
        $blogModel = new Blog();
        $blogs = $blogModel->all("id DESC");

        $this->renderAdmin('blog/index', [
            'blogs' => $blogs,
            'title' => 'Art Stories & Magazine — JayArt Admin'
        ]);
    }

    public function create(): void {
        $this->renderAdmin('blog/form', [
            'blog'   => null,
            'seo'    => null,
            'isEdit' => false,
            'title'  => 'Write New Art Story — JayArt Admin'
        ]);
    }

    public function store(): void {
        $blogModel = new Blog();

        $title = trim((string)($_POST['title'] ?? ''));
        $slug = Validator::slugify($title);
        if ($blogModel->findBySlug($slug)) {
            $slug .= '-' . time();
        }

        $imagePath = null;
        if (!empty($_FILES['featured_image']['name'])) {
            $ext = strtolower(pathinfo($_FILES['featured_image']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'], true)) {
                $filename = 'blog_' . time() . '.' . $ext;
                $dest = UPLOADS_PATH . '/blog/' . $filename;
                if (move_uploaded_file($_FILES['featured_image']['tmp_name'], $dest)) {
                    $imagePath = 'uploads/blog/' . $filename;
                }
            }
        }

        $blogId = $blogModel->insert([
            'title'          => $title,
            'slug'           => $slug,
            'excerpt'        => trim((string)($_POST['excerpt'] ?? '')),
            'content'        => trim((string)($_POST['content'] ?? '')),
            'featured_image' => $imagePath,
            'author_name'    => trim((string)($_POST['author_name'] ?? 'JayArt Curators')),
            'status'         => $_POST['status'] ?? 'published',
        ]);

        // Save Dedicated Per-Blog SEO
        $metaTitle = trim((string)($_POST['meta_title'] ?? ''));
        $metaDesc  = trim((string)($_POST['meta_description'] ?? ''));
        $keywords  = trim((string)($_POST['keywords'] ?? ''));
        $ogImage   = trim((string)($_POST['og_image'] ?? ''));

        if (!empty($_FILES['blog_og_image_file']['tmp_name']) && is_uploaded_file($_FILES['blog_og_image_file']['tmp_name'])) {
            $file = $_FILES['blog_og_image_file'];
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'svg'], true)) {
                $ogDir = ROOT_PATH . '/uploads/blogs/og';
                if (!is_dir($ogDir)) @mkdir($ogDir, 0755, true);
                $ogFilename = 'og-blog-' . time() . '-' . uniqid() . '.' . $ext;
                if (move_uploaded_file($file['tmp_name'], $ogDir . '/' . $ogFilename)) {
                    $ogImage = 'uploads/blogs/og/' . $ogFilename;
                }
            }
        }

        $db = Database::connect();
        $db->prepare("INSERT INTO `blog_seo` (`blog_id`, `meta_title`, `meta_description`, `keywords`, `canonical_url`, `og_image`) VALUES (:bid, :title, :desc, :kw, :canon, :og)")
           ->execute([
               'bid'   => $blogId,
               'title' => $metaTitle ?: ($title . ' | JayArt Magazine'),
               'desc'  => $metaDesc ?: substr(strip_tags((string)$_POST['content']), 0, 155),
               'kw'    => $keywords,
               'canon' => SITE_URL . '/blog/' . $slug,
               'og'    => $ogImage !== '' ? $ogImage : null
           ]);

        Cache::deletePattern('home:*');
        Auth::setFlash('success', 'Art story published with dedicated article SEO metadata.');
        header('Location: ' . ADMIN_URL . '/blog');
        exit;
    }

    public function edit(int $id): void {
        $blogModel = new Blog();
        $blog = $blogModel->find($id);

        if (!$blog) {
            header('Location: ' . ADMIN_URL . '/blog');
            exit;
        }

        $db = Database::connect();
        $stmt = $db->prepare("SELECT * FROM `blog_seo` WHERE `blog_id` = :bid LIMIT 1");
        $stmt->execute(['bid' => $id]);
        $seo = $stmt->fetch() ?: null;

        $this->renderAdmin('blog/form', [
            'blog'   => $blog,
            'seo'    => $seo,
            'isEdit' => true,
            'title'  => 'Edit Story — ' . $blog['title']
        ]);
    }

    public function update(int $id): void {
        $blogModel = new Blog();
        $blog = $blogModel->find($id);

        if (!$blog) {
            header('Location: ' . ADMIN_URL . '/blog');
            exit;
        }

        $title = trim((string)($_POST['title'] ?? ''));
        $data = [
            'title'       => $title,
            'excerpt'     => trim((string)($_POST['excerpt'] ?? '')),
            'content'     => trim((string)($_POST['content'] ?? '')),
            'author_name' => trim((string)($_POST['author_name'] ?? 'JayArt Curators')),
            'status'      => $_POST['status'] ?? 'published',
        ];

        if (!empty($_FILES['featured_image']['name'])) {
            $ext = strtolower(pathinfo($_FILES['featured_image']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'], true)) {
                $filename = 'blog_' . time() . '.' . $ext;
                $dest = UPLOADS_PATH . '/blog/' . $filename;
                if (move_uploaded_file($_FILES['featured_image']['tmp_name'], $dest)) {
                    $data['featured_image'] = 'uploads/blog/' . $filename;
                }
            }
        }

        $blogModel->update($id, $data);

        // Update Per-Blog SEO
        $metaTitle = trim((string)($_POST['meta_title'] ?? ''));
        $metaDesc  = trim((string)($_POST['meta_description'] ?? ''));
        $keywords  = trim((string)($_POST['keywords'] ?? ''));
        $ogImage   = trim((string)($_POST['og_image'] ?? ''));

        if (!empty($_FILES['blog_og_image_file']['tmp_name']) && is_uploaded_file($_FILES['blog_og_image_file']['tmp_name'])) {
            $file = $_FILES['blog_og_image_file'];
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'svg'], true)) {
                $ogDir = ROOT_PATH . '/uploads/blogs/og';
                if (!is_dir($ogDir)) @mkdir($ogDir, 0755, true);
                $ogFilename = 'og-blog-' . time() . '-' . uniqid() . '.' . $ext;
                if (move_uploaded_file($file['tmp_name'], $ogDir . '/' . $ogFilename)) {
                    $ogImage = 'uploads/blogs/og/' . $ogFilename;
                }
            }
        }

        $db = Database::connect();
        $db->prepare("INSERT INTO `blog_seo` (`blog_id`, `meta_title`, `meta_description`, `keywords`, `canonical_url`, `og_image`) 
                      VALUES (:bid, :title, :desc, :kw, :canon, :og) 
                      ON DUPLICATE KEY UPDATE `meta_title` = :t2, `meta_description` = :d2, `keywords` = :k2, `og_image` = :og2")
           ->execute([
               'bid'   => $id,
               'title' => $metaTitle,
               'desc'  => $metaDesc,
               'kw'    => $keywords,
               'canon' => SITE_URL . '/blog/' . $blog['slug'],
               'og'    => $ogImage !== '' ? $ogImage : null,
               't2'    => $metaTitle,
               'd2'    => $metaDesc,
               'k2'    => $keywords,
               'og2'   => $ogImage !== '' ? $ogImage : null,
           ]);

        Cache::deletePattern('home:*');
        Auth::setFlash('success', 'Story and individual SEO metadata updated.');
        header('Location: ' . ADMIN_URL . '/blog');
        exit;
    }

    public function delete(int $id): void {
        $blogModel = new Blog();
        $blogModel->delete($id);
        Cache::deletePattern('home:*');
        Auth::setFlash('success', 'Story deleted.');
        header('Location: ' . ADMIN_URL . '/blog');
        exit;
    }
}

