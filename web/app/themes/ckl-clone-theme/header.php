<?php
/**
 * Helper function to get my account URL safely
 */
function ckl_get_my_account_url() {
    if (function_exists('wc_get_page_permalink')) {
        return wc_get_page_permalink('myaccount');
    }
    return wp_login_url(home_url());
}

/**
 * Helper function to get shop URL safely
 */
function ckl_get_shop_url() {
    if (function_exists('wc_get_page_id')) {
        return get_permalink(wc_get_page_id('shop'));
    }
    return home_url('/shop/');
}
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php wp_title('|', true, 'right'); ?><?php bloginfo('name'); ?></title>
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<?php wp_body_open(); ?>

<!-- Header -->
<header class="bg-white shadow-md sticky top-0 z-50">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between py-4">

            <!-- Logo -->
            <div class="flex items-center">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="flex items-center">
                    <?php
                    $custom_logo_id = get_theme_mod('custom_logo');
                    $logo = wp_get_attachment_image_src($custom_logo_id, 'full');

                    if (has_custom_logo()) {
                        echo '<img src="' . esc_url($logo[0]) . '" alt="' . esc_attr(get_bloginfo('name')) . '" class="h-12 w-auto custom-logo">';
                    } else {
                        echo '<span class="text-2xl font-bold text-primary">' . esc_html(get_bloginfo('name')) . '</span>';
                    }
                    ?>
                </a>
            </div>

            <!-- Desktop Navigation -->
            <nav class="hidden lg:flex items-center gap-8">
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'primary',
                    'container' => false,
                    'menu_class' => 'hidden lg:flex items-center gap-8',
                    'fallback_cb' => function() { ckl_default_menu(array('mobile' => false)); },
                    'walker' => new CKL_Menu_Walker(),
                ));
                ?>
            </nav>

            <!-- Action Buttons -->
            <div class="hidden lg:flex items-center space-x-4">
                <?php if (is_user_logged_in()) : ?>
                    <a href="<?php echo esc_url(ckl_get_my_account_url()); ?>"
                       class="text-gray-700 hover:text-primary font-medium transition">
                        <?php esc_html_e('My Account', 'ckl-car-rental'); ?>
                    </a>
                    <a href="<?php echo esc_url(wp_logout_url(home_url())); ?>"
                       class="bg-primary text-white px-4 py-2 rounded hover:bg-primary/90 transition">
                        <?php esc_html_e('Sign Out', 'ckl-car-rental'); ?>
                    </a>
                <?php else : ?>
                    <a href="<?php echo esc_url(ckl_get_my_account_url()); ?>"
                       class="text-gray-700 hover:text-primary font-medium transition">
                        <?php esc_html_e('Sign In', 'ckl-car-rental'); ?>
                    </a>
                    <a href="<?php echo esc_url(ckl_get_shop_url()); ?>"
                       class="bg-primary text-white px-4 py-2 rounded hover:bg-primary/90 transition">
                        <?php esc_html_e('Book Now', 'ckl-car-rental'); ?>
                    </a>
                <?php endif; ?>
            </div>

            <!-- Mobile Menu Button -->
            <button id="mobile-menu-toggle"
                    class="lg:hidden p-2 rounded hover:bg-gray-100"
                    aria-label="Toggle menu"
                    aria-expanded="false">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>

        </div>
    </div>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="hidden lg:hidden bg-white border-t">
        <div class="container mx-auto px-4 py-4">
            <?php
            wp_nav_menu(array(
                'theme_location' => 'primary',
                'container' => false,
                'menu_class' => 'flex flex-col space-y-4',
                'fallback_cb' => function() { ckl_default_menu(array('mobile' => true)); },
            ));
            ?>
            <div class="flex flex-col space-y-4 mt-4 pt-4 border-t">
                <?php if (is_user_logged_in()) : ?>
                    <a href="<?php echo esc_url(ckl_get_my_account_url()); ?>"
                       class="text-gray-700 hover:text-primary font-medium transition">
                        <?php esc_html_e('My Account', 'ckl-car-rental'); ?>
                    </a>
                    <a href="<?php echo esc_url(wp_logout_url(home_url())); ?>"
                       class="bg-primary text-white px-4 py-2 rounded hover:bg-primary/90 transition text-center">
                        <?php esc_html_e('Sign Out', 'ckl-car-rental'); ?>
                    </a>
                <?php else : ?>
                    <a href="<?php echo esc_url(ckl_get_my_account_url()); ?>"
                       class="text-gray-700 hover:text-primary font-medium transition">
                        <?php esc_html_e('Sign In', 'ckl-car-rental'); ?>
                    </a>
                    <a href="<?php echo esc_url(ckl_get_shop_url()); ?>"
                       class="bg-primary text-white px-4 py-2 rounded hover:bg-primary/90 transition text-center">
                        <?php esc_html_e('Book Now', 'ckl-car-rental'); ?>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</header>

<?php
/**
 * Custom Menu Walker for dropdown menus
 */
class CKL_Menu_Walker extends Walker_Nav_Menu {
    function start_lvl(&$output, $depth = 0, $args = array()) {
        $indent = str_repeat("\t", $depth);
        $output .= "\n$indent<ul class=\"absolute hidden group-hover:block bg-white shadow-lg mt-2 rounded min-w-48 z-50\">\n";
    }

    function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;

        if (in_array('menu-item-has-children', $classes)) {
            $classes[] = 'relative group';
        }

        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

        $output .= '<li' . $class_names . '>';

        $attributes = !empty($item->attr_title) ? ' title="' . esc_attr($item->attr_title) . '"' : '';
        $attributes .= !empty($item->target) ? ' target="' . esc_attr($item->target) . '"' : '';
        $attributes .= !empty($item->xfn) ? ' rel="' . esc_attr($item->xfn) . '"' : '';
        $attributes .= !empty($item->url) ? ' href="' . esc_url($item->url) . '"' : '';

        $item_output = $args->before;
        $item_output .= '<a' . $attributes . ' class="flex items-center">';

        if (in_array('menu-item-has-children', $classes)) {
            $item_output .= $args->link_before . apply_filters('the_title', $item->title, $item->ID) . $args->link_after;
            $item_output .= '<svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>';
        } else {
            $item_output .= $args->link_before . apply_filters('the_title', $item->title, $item->ID) . $args->link_after;
        }

        $item_output .= '</a>';
        $item_output .= $args->after;

        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }
}
?>
