<?php
/**
 * Front Page Template
 *
 * Homepage template with configurable sections
 * Sections: Hero, Mobile Search, How It Works, Vehicle Grid, Reviews, FAQ, News
 */

get_header();

// Get homepage sections settings
$homepage_sections = get_option('ckl_homepage_sections', ckl_get_default_homepage_sections());

// Sort sections by order
uasort($homepage_sections, function($a, $b) {
    $order_a = isset($a['order']) ? intval($a['order']) : 999;
    $order_b = isset($b['order']) ? intval($b['order']) : 999;
    return $order_a - $order_b;
});

/**
 * Template part mapping
 */
$template_parts = array(
    'hero' => 'template-parts/home/hero',
    'mobile_search' => 'template-parts/home/mobile-search',
    'how_it_works' => 'template-parts/home/how-it-works',
    'vehicle_grid' => 'template-parts/home/vehicle-grid',
    'reviews' => 'template-parts/home/reviews',
    'faq' => 'template-parts/home/faq',
    'news_section' => 'template-parts/home/news-section',
);

/**
 * Render homepage sections
 */
foreach ($homepage_sections as $section => $config) {
    // Skip if disabled
    if (!isset($config['enabled']) || !$config['enabled']) {
        continue;
    }

    // Check if template part exists
    if (isset($template_parts[$section])) {
        get_template_part($template_parts[$section]);
    }
}

get_footer();
