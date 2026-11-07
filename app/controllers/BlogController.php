<?php
/**
 * JayArt Art Stories & Blog Controller
 */

declare(strict_types=1);

class BlogController extends Controller {
    public function index(): void {
        $blogModel = new Blog();
        $posts = $blogModel->getPublished(12);

        $this->render('blog/index', [
            'posts' => $posts,
            'seo'   => [
                'title'       => 'Art Stories, Curator Perspectives & Collecting Insights | JayArt',
                'description' => 'Explore stories on contemporary Indian painting techniques, impasto oils, watercolor conservation, and art collecting guides.',
                'og_type'     => 'website'
            ]
        ]);
    }

    public function post(string $slug): void {
        $blogModel = new Blog();
        $post = $blogModel->findBySlug($slug);

        if (!$post) {
            http_response_code(404);
            $this->render('errors/404');
            return;
        }

        $blogModel->incrementViews((int)$post['id']);

        $featuredImg = !empty($post['featured_image']) ? SITE_URL . '/' . $post['featured_image'] : ASSETS_URL . '/images/banner.jpg';
        $articleSchema = SEO::buildArticleSchema($post, $featuredImg);

        $metaTitle = !empty($post['meta_title']) ? $post['meta_title'] : $post['title'] . ' | JayArt Magazine';
        $metaDesc  = !empty($post['meta_description']) ? $post['meta_description'] : substr(strip_tags((string)$post['content']), 0, 155);

        $ogImage = !empty($post['og_image']) ? SITE_URL . '/' . ltrim($post['og_image'], '/') : $featuredImg;

        $this->render('blog/post', [
            'post'        => $post,
            'featuredImg' => $featuredImg,
            'seo'         => [
                'title'       => $metaTitle,
                'description' => $metaDesc,
                'keywords'    => $post['keywords'] ?? '',
                'og_image'    => $ogImage,
                'og_type'     => 'article',
                'canonical'   => SITE_URL . '/blog/' . $post['slug'],
                'schema'      => $articleSchema,
            ]
        ]);
    }
}

