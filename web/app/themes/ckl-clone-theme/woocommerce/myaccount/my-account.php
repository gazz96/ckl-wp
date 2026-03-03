<?php
/**
 * My Account
 *
 * Override WooCommerce my-account template with fixed sidebar layout
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to maintain compatibility.
 *
 * @see     https://woo.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 9.6.0
 */

defined('ABSPATH') || exit;

// If user is NOT logged in, show custom login form
if (!is_user_logged_in()) {
    wc_get_template_part('myaccount/form-login');
    return;
}

get_header();

// Get current user
$current_user = wp_get_current_user();
?>

<div class="ckl-my-account-wrapper min-h-screen flex flex-col">
    <!-- Mobile Header with Hamburger -->
    <div class="ckl-my-account-mobile-header lg:hidden sticky top-0 z-50 bg-white border-b border-gray-200">
        <button id="ckl-menu-toggle" class="ckl-menu-toggle flex items-center gap-2 p-3 w-full">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
            <span class="font-semibold text-gray-800">My Account Menu</span>
        </button>
    </div>

    <!-- Main Layout Container -->
    <div class="ckl-my-account-container flex flex-1">
        <!-- Sidebar Navigation -->
        <aside id="ckl-my-account-sidebar"
               class="ckl-my-account-sidebar
                      w-64 bg-white border-r border-gray-200
                      flex-shrink-0
                      lg:relative lg:h-[calc(100vh-5rem)] lg:overflow-hidden
                      fixed inset-y-0 left-0 z-40 h-full
                      transform -translate-x-full lg:translate-x-0
                      transition-transform duration-300
                      flex flex-col">
            <?php
            /**
             * CKL: Custom sidebar navigation with user profile summary
             */
            wc_get_template_part('template-parts/myaccount/navigation');
            ?>
        </aside>

        <!-- Main Content Area -->
        <main class="ckl-my-account-content flex-1 min-w-0 bg-gray-50 lg:h-[calc(100vh-5rem)] lg:overflow-y-auto">
            <div class="ckl-my-account-content-inner p-4 md:p-6 lg:p-8 max-w-7xl mx-auto">
                <?php
                /**
                 * My Account content.
                 *
                 * CKL: Call our custom endpoint content function directly
                 * to avoid duplicate content from WooCommerce default handlers
                 */
                ckl_account_endpoint_content();
                ?>
            </div>
        </main>
    </div>

    <!-- Overlay for mobile sidebar -->
    <div id="ckl-sidebar-overlay"
         class="fixed inset-0 bg-black/50 z-30 hidden lg:hidden backdrop-blur-sm">
    </div>
</div>

<script>
// Mobile sidebar toggle
(function() {
    const menuToggle = document.getElementById('ckl-menu-toggle');
    const sidebar = document.getElementById('ckl-my-account-sidebar');
    const overlay = document.getElementById('ckl-sidebar-overlay');
    const wrapper = document.querySelector('.ckl-my-account-wrapper');

    if (menuToggle && sidebar && overlay) {
        function openSidebar() {
            sidebar.classList.remove('-translate-x-full');
            sidebar.classList.add('translate-x-0');
            overlay.classList.remove('hidden');
            if (wrapper) wrapper.classList.add('sidebar-open');
            document.body.style.overflow = 'hidden';
        }

        function closeSidebar() {
            sidebar.classList.add('-translate-x-full');
            sidebar.classList.remove('translate-x-0');
            overlay.classList.add('hidden');
            if (wrapper) wrapper.classList.remove('sidebar-open');
            document.body.style.overflow = '';
        }

        menuToggle.addEventListener('click', openSidebar);
        overlay.addEventListener('click', closeSidebar);

        // Close sidebar when clicking on navigation links (mobile)
        const navLinks = sidebar.querySelectorAll('a');
        navLinks.forEach(link => {
            link.addEventListener('click', closeSidebar);
        });

        // Handle escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeSidebar();
        });
    }
})();
</script>

<?php get_footer(); ?>
