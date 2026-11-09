<?php
/**
 * JayArt Home Controller
 */

declare(strict_types=1);

class HomeController extends Controller {
    public function index(): void {
        $productModel = new Product();
        $categoryModel = new Category();
        $blogModel = new Blog();

        $featuredPaintings = $productModel->getFeatured(8);
        $categories = $categoryModel->getActiveWithCounts();

        $homepageTtl = (int)Cache::getConfig()['homepage_ttl'];
        $recentBlogs = Cache::remember('home:recent_blogs', $homepageTtl, fn() => $blogModel->getPublished(3));
        $activeCoupons = Cache::remember('home:active_coupons', $homepageTtl, fn() => Coupon::allActive());

        $seo = SEO::getGlobalSettings();

        $this->render('home/index', [
            'featuredProducts'  => $featuredPaintings,
            'featuredPaintings' => $featuredPaintings,
            'categories'        => $categories,
            'latestBlogs'       => $recentBlogs,
            'recentBlogs'       => $recentBlogs,
            'activeCoupons'     => $activeCoupons,
            'seo'               => [
                'title'       => $seo['site_title'],
                'description' => $seo['default_meta_desc'],
                'keywords'    => $seo['default_keywords'],
                'og_image'    => $seo['default_og_image'],
                'og_type'     => 'website'
            ]
        ]);
    }
}

