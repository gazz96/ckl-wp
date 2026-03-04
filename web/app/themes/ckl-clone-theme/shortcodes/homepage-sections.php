<?php
/**
 * Homepage Sections Shortcodes Loader
 *
 * Loads all homepage section shortcodes for use throughout the site
 *
 * @package CKL_Car_Rental
 */

defined('ABSPATH') || exit;

// Load helper functions first
require_once __DIR__ . '/../includes/shortcode-helpers.php';

// Load individual section shortcodes
require_once __DIR__ . '/hero-shortcode.php';
require_once __DIR__ . '/mobile-search-shortcode.php';
require_once __DIR__ . '/how-it-works-shortcode.php';
require_once __DIR__ . '/vehicle-grid-shortcode.php';
require_once __DIR__ . '/reviews-shortcode.php';
require_once __DIR__ . '/faq-shortcode.php';
require_once __DIR__ . '/news-section-shortcode.php';
