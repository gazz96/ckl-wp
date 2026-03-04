<?php
/**
 * QuickSwap Modern Admin Dashboard Template
 *
 * Full dashboard page override using Tailwind CSS
 *
 * @package QuickSwap
 * @since 1.2.0
 */

defined('ABSPATH') || exit;

// Get current user info
$current_user = wp_get_current_user();
$settings = QuickSwap_Admin_Dashboard::get_settings();

// Enqueue QuickSwap assets early for this template
// This is necessary because the template override exits before
// admin_enqueue_scripts hook can run properly
add_action('admin_head', function() {
    if (current_user_can('quickswap_use_search')) {
        // Enqueue QuickSwap core CSS
        wp_enqueue_style(
            'quickswap',
            QUICKSWAP_PLUGIN_URL . 'assets/css/quickswap.css',
            array(),
            QUICKSWAP_VERSION
        );

        // Enqueue QuickSwap admin CSS
        wp_enqueue_style(
            'quickswap-admin',
            QUICKSWAP_PLUGIN_URL . 'assets/css/quickswap-admin.css',
            array('quickswap'),
            QUICKSWAP_VERSION
        );

        // Enqueue QuickSwap fuzzy JS
        wp_enqueue_script(
            'quickswap-fuzzy',
            QUICKSWAP_PLUGIN_URL . 'assets/js/quickswap-fuzzy.js',
            array(),
            QUICKSWAP_VERSION,
            true
        );

        // Enqueue QuickSwap keyboard JS
        wp_enqueue_script(
            'quickswap-keyboard',
            QUICKSWAP_PLUGIN_URL . 'assets/js/quickswap-keyboard.js',
            array('quickswap-fuzzy'),
            QUICKSWAP_VERSION,
            true
        );

        // Enqueue QuickSwap search JS
        wp_enqueue_script(
            'quickswap-search',
            QUICKSWAP_PLUGIN_URL . 'assets/js/quickswap-search.js',
            array('quickswap-keyboard'),
            QUICKSWAP_VERSION,
            true
        );

        // Enqueue QuickSwap core JS
        wp_enqueue_script(
            'quickswap',
            QUICKSWAP_PLUGIN_URL . 'assets/js/quickswap.js',
            array('quickswap-search', 'jquery'),
            QUICKSWAP_VERSION,
            true
        );

        // Localize script with QuickSwap data
        $settings = get_option('quickswap_settings', array());
        $keyboard_shortcut = $settings['keyboard_shortcut'] ?? 'cmd+k';
        wp_localize_script('quickswap', 'quickswapData', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('quickswap_search_nonce'),
            'restUrl' => rest_url('quickswap/v1/'),
            'settings' => array(
                'keyboardShortcut' => $keyboard_shortcut,
                'maxResults' => intval($settings['max_results'] ?? 10),
                'enableFuzzy' => !empty($settings['enable_fuzzy']),
                'fuzzyThreshold' => intval($settings['fuzzy_threshold'] ?? 70),
            ),
            'i18n' => array(
                'searchPlaceholder' => __('Search anything...', 'quickswap'),
                'noResults' => __('No results found', 'quickswap'),
                'loading' => __('Loading...', 'quickswap'),
                'error' => __('An error occurred. Please try again.', 'quickswap'),
                'open' => __('Open', 'quickswap'),
                'edit' => __('Edit', 'quickswap'),
                'view' => __('View', 'quickswap'),
                'activate' => __('Activate', 'quickswap'),
                'deactivate' => __('Deactivate', 'quickswap'),
                'customize' => __('Customize', 'quickswap'),
                'settings' => __('Settings', 'quickswap'),
            ),
        ));
    }
}, 1);

// Get current user info
$current_user = wp_get_current_user();
$settings = QuickSwap_Admin_Dashboard::get_settings();
?>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo esc_html(get_bloginfo('name')) . ' - ' . esc_html__('Dashboard', 'quickswap'); ?></title>
    <?php
    // Enqueue WordPress admin styles BEFORE wp_head()
    wp_enqueue_style('wp-admin');
    wp_enqueue_style('colors');
    wp_enqueue_style('buttons');
    wp_enqueue_style('forms');
    wp_enqueue_style('common');
    wp_enqueue_style('admin-menu');
    wp_enqueue_style('dashicons');
    wp_enqueue_style('admin-bar');

    // Enqueue Tailwind if available
    $theme_dir = get_template_directory();
    if (file_exists($theme_dir . '/assets/css/main.css')) {
        wp_enqueue_style(
            'quickswap-tailwind',
            get_template_directory_uri() . '/assets/css/main.css',
            array(),
            QUICKSWAP_VERSION
        );
    }

    wp_head();
    ?>
</head>
<?php
global $current_screen;
$admin_body_classes = '';

// Add WordPress admin classes
$admin_body_classes .= 'wp-admin wp-core-ui no-js ';

// Add color scheme class
$admin_color = get_user_meta(get_current_user_id(), 'admin_color', true);
if (empty($admin_color)) {
    $admin_color = 'fresh';
}
$admin_body_classes .= 'admin-color-' . esc_attr($admin_color) . ' ';

// Add folded/unfolded class
if (is_admin() && get_user_setting('mfold') === 'f') {
    $admin_body_classes .= 'folded ';
} else {
    $admin_body_classes .= 'auto-fold ';
}

// Add locale class
$admin_body_classes .= 'locale-' . sanitize_html_class(strtolower(substr(get_user_locale(), 0, 2))) . ' ';

// Add branch version class (e.g., wp-6-7)
$wp_version = get_bloginfo('version');
$version_parts = explode('.', $wp_version);
if (isset($version_parts[0]) && isset($version_parts[1])) {
    $admin_body_classes .= 'wp-' . $version_parts[0] . '-' . $version_parts[1] . ' ';
}

// Add custom class
$admin_body_classes .= 'quickswap-dashboard-body';
?>
<body class="<?php echo esc_attr($admin_body_classes); ?>">
<?php
do_action('admin_notices');

// Enqueue admin scripts AFTER wp_head()
wp_enqueue_script('common');
wp_enqueue_script('utils');
wp_enqueue_script('svg-painter');
wp_enqueue_script('hoverIntent');
?>
<div id="adminmenumain" role="navigation" aria-label="Main menu">
    <?php
    // Include WordPress admin menu
    require_once(ABSPATH . 'wp-admin/menu-header.php');
    ?>
</div>

<!-- Add proper WordPress body wrapper -->
<div id="wpbody" role="main">
    <div id="wpbody-content">
        <!-- Add spacing for main content to account for sidebar -->
        <style>
            .quickswap-dashboard-wrapper {
                margin-left: 160px !important; /* Width of expanded admin menu */
            }
            .auto-fold .quickswap-dashboard-wrapper {
                margin-left: 160px !important;
            }
            .folded .quickswap-dashboard-wrapper {
                margin-left: 36px !important; /* Width of collapsed admin menu */
            }
            @media (max-width: 960px) {
                .quickswap-dashboard-wrapper {
                    margin-left: 0 !important;
                }
            }
        </style>

        <div class="quickswap-dashboard-wrapper min-h-screen bg-gray-50/50">
    <div class="quickswap-dashboard-container max-w-[1600px] mx-auto p-6">
        <!-- Dashboard Header -->
        <header class="quickswap-dashboard-header mb-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">
                        <?php esc_html_e('Dashboard', 'quickswap'); ?>
                    </h1>
                    <p class="text-sm text-gray-500 mt-1">
                        <?php
                        printf(
                            /* translators: %s: user display name */
                            esc_html__('Welcome back, %s! Here\'s what\'s happening.', 'quickswap'),
                            esc_html($current_user->display_name)
                        );
                        ?>
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="<?php echo esc_url(admin_url('post-new.php')); ?>"
                       class="inline-flex items-center gap-2 px-4 py-2 bg-primary text-primary-foreground rounded-lg text-sm font-medium hover:bg-primary/90 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        <?php esc_html_e('New Post', 'quickswap'); ?>
                    </a>
                    <a href="<?php echo esc_url(admin_url('profile.php')); ?>"
                       class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-border text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <?php esc_html_e('Settings', 'quickswap'); ?>
                    </a>
                </div>
            </div>
        </header>

        <!-- Quick Stats Row -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <?php
            $total_posts = wp_count_posts('post');
            $total_pages = wp_count_posts('page');
            $published_posts = $total_posts->publish;
            $draft_posts = $total_posts->draft;
            $pending_posts = $total_posts->pending;
            $total_users = count_users();
            $total_comments = wp_count_comments()->approved;
            ?>

            <!-- Published Posts -->
            <div class="bg-white rounded-xl shadow-sm border border-border p-5 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-muted-foreground uppercase tracking-wide"><?php esc_html_e('Published', 'quickswap'); ?></p>
                        <p class="text-2xl font-bold text-gray-900 mt-1"><?php echo esc_html($published_posts); ?></p>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-3">
                    <?php
                    printf(
                        esc_html__('%s total posts', 'quickswap'),
                        esc_html($published_posts + $draft_posts + $pending_posts)
                    );
                    ?>
                </p>
            </div>

            <!-- Draft Posts -->
            <div class="bg-white rounded-xl shadow-sm border border-border p-5 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-muted-foreground uppercase tracking-wide"><?php esc_html_e('Drafts', 'quickswap'); ?></p>
                        <p class="text-2xl font-bold text-gray-900 mt-1"><?php echo esc_html($draft_posts); ?></p>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-yellow-100 flex items-center justify-center">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-3">
                    <?php
                    printf(
                        esc_html__('%s pending review', 'quickswap'),
                        esc_html($pending_posts)
                    );
                    ?>
                </p>
            </div>

            <!-- Team Members -->
            <div class="bg-white rounded-xl shadow-sm border border-border p-5 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-muted-foreground uppercase tracking-wide"><?php esc_html_e('Team', 'quickswap'); ?></p>
                        <p class="text-2xl font-bold text-gray-900 mt-1"><?php echo esc_html($total_users['total_users']); ?></p>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-3">
                    <a href="<?php echo esc_url(admin_url('users.php')); ?>" class="text-primary hover:underline">
                        <?php esc_html_e('View all members', 'quickswap'); ?>
                    </a>
                </p>
            </div>

            <!-- Comments -->
            <div class="bg-white rounded-xl shadow-sm border border-border p-5 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-muted-foreground uppercase tracking-wide"><?php esc_html_e('Comments', 'quickswap'); ?></p>
                        <p class="text-2xl font-bold text-gray-900 mt-1"><?php echo esc_html($total_comments); ?></p>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-purple-100 flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-3">
                    <a href="<?php echo esc_url(admin_url('edit-comments.php')); ?>" class="text-primary hover:underline">
                        <?php esc_html_e('Manage comments', 'quickswap'); ?>
                    </a>
                </p>
            </div>
        </div>

        <!-- Main Dashboard Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Projects Overview - 8 columns -->
            <?php if (QuickSwap_Admin_Dashboard::is_widget_enabled('projects_overview')) : ?>
                <div class="lg:col-span-8">
                    <div class="bg-white rounded-xl shadow-sm border border-border overflow-hidden">
                        <div class="px-6 py-4 border-b border-border flex items-center justify-between">
                            <h2 class="text-base font-semibold text-gray-900">
                                <?php esc_html_e('Projects Overview', 'quickswap'); ?>
                            </h2>
                            <a href="<?php echo esc_url(admin_url('edit.php')); ?>"
                               class="text-sm text-primary hover:underline">
                                <?php esc_html_e('View All', 'quickswap'); ?>
                            </a>
                        </div>
                        <div class="p-0">
                            <?php QuickSwap_Admin_Dashboard::render_projects_overview_widget(); ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Progress Chart - 4 columns -->
            <?php if (QuickSwap_Admin_Dashboard::is_widget_enabled('progress_chart')) : ?>
                <div class="lg:col-span-4">
                    <div class="bg-white rounded-xl shadow-sm border border-border overflow-hidden">
                        <div class="px-6 py-4 border-b border-border">
                            <h2 class="text-base font-semibold text-gray-900">
                                <?php esc_html_e('My Progress', 'quickswap'); ?>
                            </h2>
                        </div>
                        <div class="p-6">
                            <?php QuickSwap_Admin_Dashboard::render_progress_chart_widget(); ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Team Members - 6 columns -->
            <?php if (QuickSwap_Admin_Dashboard::is_widget_enabled('team_members')) : ?>
                <div class="lg:col-span-6">
                    <div class="bg-white rounded-xl shadow-sm border border-border overflow-hidden">
                        <div class="px-6 py-4 border-b border-border flex items-center justify-between">
                            <h2 class="text-base font-semibold text-gray-900">
                                <?php esc_html_e('Team Members', 'quickswap'); ?>
                            </h2>
                            <a href="<?php echo esc_url(admin_url('users.php')); ?>"
                               class="text-sm text-primary hover:underline">
                                <?php esc_html_e('View All', 'quickswap'); ?>
                            </a>
                        </div>
                        <div class="p-6">
                            <?php QuickSwap_Admin_Dashboard::render_team_members_widget(); ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Activity Log - 6 columns -->
            <?php if (QuickSwap_Admin_Dashboard::is_widget_enabled('activity_log')) : ?>
                <div class="lg:col-span-6">
                    <div class="bg-white rounded-xl shadow-sm border border-border overflow-hidden">
                        <div class="px-6 py-4 border-b border-border">
                            <h2 class="text-base font-semibold text-gray-900">
                                <?php esc_html_e('Recent Activity', 'quickswap'); ?>
                            </h2>
                        </div>
                        <div class="p-6">
                            <?php QuickSwap_Admin_Dashboard::render_activity_log_widget(); ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- My Tasks - 4 columns -->
            <?php if (QuickSwap_Admin_Dashboard::is_widget_enabled('my_tasks')) : ?>
                <div class="lg:col-span-4">
                    <div class="bg-white rounded-xl shadow-sm border border-border overflow-hidden">
                        <div class="px-6 py-4 border-b border-border">
                            <h2 class="text-base font-semibold text-gray-900">
                                <?php esc_html_e('My Tasks', 'quickswap'); ?>
                            </h2>
                        </div>
                        <div class="p-0">
                            <?php QuickSwap_Admin_Dashboard::render_my_tasks_widget(); ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Budget Overview - 4 columns -->
            <?php if (QuickSwap_Admin_Dashboard::is_widget_enabled('budget_overview')) : ?>
                <div class="lg:col-span-4">
                    <div class="bg-white rounded-xl shadow-sm border border-border overflow-hidden">
                        <div class="px-6 py-4 border-b border-border">
                            <h2 class="text-base font-semibold text-gray-900">
                                <?php esc_html_e('Budget Overview', 'quickswap'); ?>
                            </h2>
                        </div>
                        <div class="p-6">
                            <?php QuickSwap_Admin_Dashboard::render_budget_overview_widget(); ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Upcoming Meetings - 4 columns -->
            <?php if (QuickSwap_Admin_Dashboard::is_widget_enabled('upcoming_meetings')) : ?>
                <div class="lg:col-span-4">
                    <div class="bg-white rounded-xl shadow-sm border border-border overflow-hidden">
                        <div class="px-6 py-4 border-b border-border">
                            <h2 class="text-base font-semibold text-gray-900">
                                <?php esc_html_e('Upcoming Meetings', 'quickswap'); ?>
                            </h2>
                        </div>
                        <div class="p-6">
                            <?php QuickSwap_Admin_Dashboard::render_upcoming_meetings_widget(); ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Dashboard Footer -->
        <footer class="quickswap-dashboard-footer mt-8 text-center text-sm text-gray-500">
            <p>
                <?php
                printf(
                    esc_html__('QuickSwap Modern Dashboard v%s', 'quickswap'),
                    esc_html(QUICKSWAP_VERSION)
                );
                ?>
                |
                <a href="<?php echo esc_url(admin_url('admin.php?page=quickswap-dashboard-settings')); ?>"
                   class="text-primary hover:underline">
                    <?php esc_html_e('Dashboard Settings', 'quickswap'); ?>
                </a>
            </p>
        </footer>
    </div> <!-- End quickswap-dashboard-container -->
</div> <!-- End quickswap-dashboard-wrapper -->
    </div> <!-- End wpbody-content -->
</div> <!-- End wpbody -->

<!-- Add proper WordPress footer -->
<div id="wpfooter" role="contentinfo">
    <?php
    do_action('admin_footer');
    wp_footer();
    ?>
</div>
</body>
</html>
