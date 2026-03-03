<?php
/**
 * My Account Navigation
 *
 * Custom sidebar navigation with user profile summary
 *
 * @package CKL_Car_Rental
 */

defined('ABSPATH') || exit;

$current_user = wp_get_current_user();
$customer_id = get_current_user_id();

// Get user avatar
$avatar_url = get_avatar_url($customer_id, array('size' => 80));

// Get customer's first name or username
$first_name = $current_user->first_name;
$last_name = $current_user->last_name;
$display_name = $first_name ? $first_name : ($current_user->display_name ?: $current_user->user_login);

// Get WooCommerce endpoint URLs
$endpoints = array(
    'dashboard' => wc_get_account_endpoint_url('dashboard'),
    'orders' => wc_get_account_endpoint_url('orders'),
    'bookings' => wc_get_account_endpoint_url('bookings'),
    'profile' => wc_get_account_endpoint_url('profile'),
    'documents' => wc_get_account_endpoint_url('documents'),
    'support' => wc_get_account_endpoint_url('support'),
    'logout' => wc_logout_url(),
);

// Get current endpoint
global $wp;
$current_endpoint = isset($wp->query_vars['pagid']) ? '' : (array_key_exists('pagid', $wp->query_vars) ? '' : '');
foreach ($endpoints as $endpoint_key => $endpoint_url) {
    if ($endpoint_key === 'logout') continue;
    if (wc_get_account_endpoint_url($endpoint_key) === wc_get_page_permalink('myaccount') . $wp->request) {
        $current_endpoint = $endpoint_key;
        break;
    }
}

// Alternative method to get current endpoint
$request_uri = $_SERVER['REQUEST_URI'];
$path = parse_url($request_uri, PHP_URL_PATH);
$my_account_url = wc_get_page_permalink('myaccount');
$my_account_path = parse_url($my_account_url, PHP_URL_PATH);

if (strpos($path, $my_account_path) === 0) {
    $endpoint_part = substr($path, strlen($my_account_path));
    $endpoint_part = trim($endpoint_part, '/');
    $endpoint_part = explode('/', $endpoint_part);
    $current_endpoint = $endpoint_part[0] ?: 'dashboard';
} else {
    $current_endpoint = 'dashboard';
}
?>

<!-- User Profile Summary -->
<div class="ckl-user-profile-summary p-6 border-b border-gray-200">
    <div class="flex items-center gap-4">
        <div class="relative">
            <img src="<?php echo esc_url($avatar_url); ?>" alt="<?php echo esc_attr($display_name); ?>" class="w-16 h-16 rounded-full object-cover border-2 border-[#cc2e28]">
            <span class="absolute bottom-0 right-0 w-4 h-4 bg-green-500 border-2 border-white rounded-full"></span>
        </div>
        <div class="flex-1 min-w-0">
            <h3 class="text-lg font-semibold text-gray-900 truncate"><?php echo esc_html($display_name); ?></h3>
            <p class="text-sm text-gray-500 truncate"><?php echo esc_html($current_user->user_email); ?></p>
        </div>
    </div>
</div>

<!-- Navigation Menu -->
<nav class="ckl-navigation-menu p-4">
    <ul class="space-y-1">

        <?php if (has_nav_menu('my-account')) : ?>

            <?php
            wp_nav_menu(
                array(
                    'theme_location' => 'my-account',
                    'container_class' => 'woocommerce-MyAccount-navigation',
                    'menu_class'     => 'woocommerce-MyAccount-navigation-ul',
                    'fallback_cb'    => false,
                )
            );
            ?>

        <?php else : ?>

            <?php
            // Define navigation items
            $nav_items = apply_filters('woocommerce_account_menu_items', array(
                'dashboard' => __('Dashboard', 'ckl-car-rental'),
                'orders' => __('Orders', 'woocommerce'),
                'bookings' => __('Bookings', 'ckl-car-rental'),
                'profile' => __('Profile', 'ckl-car-rental'),
                'documents' => __('Documents', 'ckl-car-rental'),
                'support' => __('Support', 'ckl-car-rental'),
                'customer-logout' => __('Logout', 'woocommerce'),
            ));

            // Remove items if they don't exist in endpoints
            foreach ($nav_items as $key => $label) {
                if ($key === 'customer-logout') {
                    continue;
                }

                $url = isset($endpoints[$key]) ? $endpoints[$key] : '';
                if (empty($url) && $key !== 'dashboard') {
                    unset($nav_items[$key]);
                }
            }

            foreach ($nav_items as $endpoint => $label) :
                $is_active = ($current_endpoint === $endpoint) ? true : false;
                $url = ($endpoint === 'customer-logout') ? $endpoints['logout'] : (isset($endpoints[$endpoint]) ? $endpoints[$endpoint] : '#');
                $icon = '';

                // Add icons for each menu item
                switch ($endpoint) {
                    case 'dashboard':
                        $icon = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>';
                        break;
                    case 'orders':
                        $icon = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>';
                        break;
                    case 'bookings':
                        $icon = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>';
                        break;
                    case 'profile':
                        $icon = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>';
                        break;
                    case 'documents':
                        $icon = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>';
                        break;
                    case 'support':
                        $icon = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>';
                        break;
                    case 'customer-logout':
                        $icon = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>';
                        break;
                }

                ?>
                <li class="woocommerce-MyAccount-navigation-link woocommerce-MyAccount-navigation-link--<?php echo esc_attr($endpoint); ?>">
                    <a href="<?php echo esc_url($url); ?>"
                       class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 <?php echo $is_active ? 'bg-[#cc2e28] text-white shadow-md' : 'text-gray-700 hover:bg-gray-100 hover:text-[#cc2e28]'; ?>">
                        <?php echo $icon; ?>
                        <span class="font-medium"><?php echo esc_html($label); ?></span>
                        <?php if ($is_active) : ?>
                            <span class="ml-auto w-2 h-2 bg-white rounded-full"></span>
                        <?php endif; ?>
                    </a>
                </li>
            <?php endforeach; ?>

        <?php endif; ?>

    </ul>
</nav>

<!-- Need Help Section -->
<div class="ckl-need-help p-6 mt-auto border-t border-gray-200">
    <div class="bg-gradient-to-br from-[#cc2e28] to-[#a8241f] rounded-xl p-4 text-white">
        <h4 class="font-semibold mb-2">Need Help?</h4>
        <p class="text-sm opacity-90 mb-3">Our support team is ready to assist you.</p>
        <a href="<?php echo esc_url(wc_get_account_endpoint_url('support')); ?>" class="inline-flex items-center gap-2 bg-white text-[#cc2e28] px-4 py-2 rounded-lg text-sm font-semibold hover:bg-gray-100 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
            </svg>
            Contact Support
        </a>
    </div>
</div>
