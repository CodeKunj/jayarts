<?php
/**
 * JayArt Product Detail Controller
 */

declare(strict_types=1);

class ProductController extends Controller {
    public function view(string $slug): void {
        $productModel = new Product();
        $reviewModel = new Review();

        $product = $productModel->findBySlug($slug);

        if (!$product) {
            http_response_code(404);
            $this->render('errors/404', [
                'seo' => ['title' => 'Artwork Not Found | JayArt']
            ]);
            return;
        }

        // Increment Views
        $productModel->incrementViews((int)$product['id']);

        // Related Artworks
        $related = $productModel->getRelated((int)$product['id'], $product['category_id'] ? (int)$product['category_id'] : null, 4);

        // Reviews
        $reviews = $reviewModel->getForProduct((int)$product['id']);
        $avgRating = $reviewModel->getAverageRating((int)$product['id']);

        // Primary Image for Schema & OpenGraph
        $primaryImg = !empty($product['images'][0]['image_path']) ? Product::imageUrl($product['images'][0]['image_path']) : ASSETS_URL . '/images/banner.jpg';

        // Rich Product Schema for Google Search Console & Rich Snippets
        $productSchema = SEO::buildProductSchema($product, $primaryImg);

        // Per-Product Custom SEO overrides
        $metaTitle = !empty($product['meta_title']) ? $product['meta_title'] : $product['title'] . ' by ' . $product['artist_name'] . ' | JayArt Gallery';
        $metaDesc = !empty($product['meta_description']) ? $product['meta_description'] : substr(strip_tags((string)$product['description']), 0, 155);
        $ogImage = !empty($product['og_image']) ? Product::imageUrl($product['og_image']) : $primaryImg;

        $this->render('shop/product', [
            'product'       => $product,
            'related'       => $related,
            'reviews'       => $reviews,
            'avgRating'     => $avgRating,
            'primaryImg'    => $primaryImg,
            'seo'           => [
                'title'       => $metaTitle,
                'description' => $metaDesc,
                'keywords'    => $product['keywords'] ?? '',
                'og_image'    => $ogImage,
                'og_type'     => 'product',
                'canonical'   => SITE_URL . '/product/' . $product['slug'],
                'schema'      => $productSchema,
            ]
        ]);
    }
}

