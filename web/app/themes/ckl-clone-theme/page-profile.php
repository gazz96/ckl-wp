<?php
/**
 * Template Name: My Profile
 *
 * My Profile page template for CK Langkawi Car Rental
 * Allows users to update their profile information
 */

// Redirect if not logged in
if (!is_user_logged_in()) {
    wp_redirect(wp_login_url(get_permalink()));
    exit;
}

get_header();

$user_id = get_current_user_id();
$user = get_userdata($user_id);

// Handle profile update
$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ckl_profile_nonce'])) {
    if (!wp_verify_nonce($_POST['ckl_profile_nonce'], 'ckl_update_profile')) {
        $error_message = __('Invalid security token. Please try again.', 'ckl-car-rental');
    } else {
        // Handle profile update
        if (isset($_POST['action']) && $_POST['action'] === 'update_profile') {
            $first_name = sanitize_text_field($_POST['first_name'] ?? '');
            $last_name = sanitize_text_field($_POST['last_name'] ?? '');
            $phone = sanitize_text_field($_POST['phone'] ?? '');
            $address = sanitize_text_field($_POST['address'] ?? '');
            $city = sanitize_text_field($_POST['city'] ?? '');
            $state = sanitize_text_field($_POST['state'] ?? '');
            $postcode = sanitize_text_field($_POST['postcode'] ?? '');
            $license_number = sanitize_text_field($_POST['license_number'] ?? '');

            // Update user meta
            update_user_meta($user_id, 'first_name', $first_name);
            update_user_meta($user_id, 'last_name', $last_name);
            update_user_meta($user_id, 'billing_phone', $phone);
            update_user_meta($user_id, 'billing_address_1', $address);
            update_user_meta($user_id, 'billing_city', $city);
            update_user_meta($user_id, 'billing_state', $state);
            update_user_meta($user_id, 'billing_postcode', $postcode);
            update_user_meta($user_id, 'license_number', $license_number);

            $success_message = __('Profile updated successfully!', 'ckl-car-rental');

            // Refresh user data
            $user = get_userdata($user_id);
        }

        // Handle password change
        if (isset($_POST['action']) && $_POST['action'] === 'change_password') {
            $current_password = $_POST['current_password'] ?? '';
            $new_password = $_POST['new_password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';

            if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
                $error_message = __('All password fields are required.', 'ckl-car-rental');
            } elseif (!wp_check_password($current_password, $user->user_pass, $user_id)) {
                $error_message = __('Current password is incorrect.', 'ckl-car-rental');
            } elseif ($new_password !== $confirm_password) {
                $error_message = __('New passwords do not match.', 'ckl-car-rental');
            } elseif (strlen($new_password) < 8) {
                $error_message = __('Password must be at least 8 characters long.', 'ckl-car-rental');
            } else {
                wp_set_password($new_password, $user_id);
                wp_set_current_user($user_id);
                wp_set_auth_cookie($user_id);
                $success_message = __('Password changed successfully!', 'ckl-car-rental');
            }
        }
    }
}

// Get user data
$first_name = get_user_meta($user_id, 'first_name', true);
$last_name = get_user_meta($user_id, 'last_name', true);
$phone = get_user_meta($user_id, 'billing_phone', true);
$address = get_user_meta($user_id, 'billing_address_1', true);
$city = get_user_meta($user_id, 'billing_city', true);
$state = get_user_meta($user_id, 'billing_state', true);
$postcode = get_user_meta($user_id, 'billing_postcode', true);
$license_number = get_user_meta($user_id, 'license_number', true);
?>

<!-- Hero Section -->
<section class="hero-section relative bg-gradient-to-r from-blue-600 to-blue-800 text-white py-16">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto text-center">
            <h1 class="text-4xl font-bold mb-4">
                <?php _e('My Profile', 'ckl-car-rental'); ?>
            </h1>
            <p class="text-lg">
                <?php _e('Manage your account settings and preferences', 'ckl-car-rental'); ?>
            </p>
        </div>
    </div>
</section>

<!-- Profile Content -->
<section class="profile-content py-12">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">

            <!-- Success/Error Messages -->
            <?php if ($success_message) : ?>
                <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-6 py-4 rounded-lg">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <p class="font-semibold"><?php echo esc_html($success_message); ?></p>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($error_message) : ?>
                <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-6 py-4 rounded-lg">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                        </svg>
                        <p class="font-semibold"><?php echo esc_html($error_message); ?></p>
                    </div>
                </div>
            <?php endif; ?>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow p-6">
                        <!-- User Info -->
                        <div class="text-center mb-6">
                            <div class="w-24 h-24 bg-accent/10 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-12 h-12 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <h3 class="font-bold text-lg">
                                <?php echo esc_html($first_name . ' ' . $last_name); ?>
                            </h3>
                            <p class="text-gray-600 text-sm">
                                <?php echo esc_html($user->user_email); ?>
                            </p>
                        </div>

                        <!-- Quick Links -->
                        <div class="space-y-2">
                            <a href="<?php echo home_url('/dashboard/'); ?>"
                               class="block px-4 py-2 rounded hover:bg-gray-100 text-gray-700">
                                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                </svg>
                                <?php _e('Dashboard', 'ckl-car-rental'); ?>
                            </a>
                            <a href="<?php echo home_url('/bookings/'); ?>"
                               class="block px-4 py-2 rounded hover:bg-gray-100 text-gray-700">
                                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <?php _e('My Bookings', 'ckl-car-rental'); ?>
                            </a>
                            <a href="<?php echo home_url('/bookmarks/'); ?>"
                               class="block px-4 py-2 rounded hover:bg-gray-100 text-gray-700">
                                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                                </svg>
                                <?php _e('Bookmarks', 'ckl-car-rental'); ?>
                            </a>
                            <?php if (in_array('administrator', $user->roles) || in_array('shop_manager', $user->roles)) : ?>
                                <a href="<?php echo admin_url(); ?>"
                                   class="block px-4 py-2 rounded hover:bg-gray-100 text-gray-700">
                                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <?php _e('Admin Panel', 'ckl-car-rental'); ?>
                                </a>
                            <?php endif; ?>
                            <a href="<?php echo wp_logout_url(home_url()); ?>"
                               class="block px-4 py-2 rounded hover:bg-red-50 text-red-600">
                                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                                <?php _e('Logout', 'ckl-car-rental'); ?>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Personal Information -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-xl font-bold mb-6 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <?php _e('Personal Information', 'ckl-car-rental'); ?>
                        </h2>

                        <form method="post" action="">
                            <?php wp_nonce_field('ckl_update_profile', 'ckl_profile_nonce'); ?>
                            <input type="hidden" name="action" value="update_profile">

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- First Name -->
                                <div>
                                    <label for="first_name" class="block text-sm font-medium mb-2">
                                        <?php _e('First Name', 'ckl-car-rental'); ?>
                                    </label>
                                    <input type="text"
                                           id="first_name"
                                           name="first_name"
                                           value="<?php echo esc_attr($first_name); ?>"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>

                                <!-- Last Name -->
                                <div>
                                    <label for="last_name" class="block text-sm font-medium mb-2">
                                        <?php _e('Last Name', 'ckl-car-rental'); ?>
                                    </label>
                                    <input type="text"
                                           id="last_name"
                                           name="last_name"
                                           value="<?php echo esc_attr($last_name); ?>"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>

                                <!-- Email (Read-only) -->
                                <div>
                                    <label for="email" class="block text-sm font-medium mb-2">
                                        <?php _e('Email Address', 'ckl-car-rental'); ?>
                                    </label>
                                    <input type="email"
                                           id="email"
                                           value="<?php echo esc_attr($user->user_email); ?>"
                                           readonly
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed">
                                    <p class="text-xs text-gray-500 mt-1">
                                        <?php _e('Contact support to change your email', 'ckl-car-rental'); ?>
                                    </p>
                                </div>

                                <!-- Phone -->
                                <div>
                                    <label for="phone" class="block text-sm font-medium mb-2">
                                        <?php _e('Phone Number', 'ckl-car-rental'); ?>
                                    </label>
                                    <input type="tel"
                                           id="phone"
                                           name="phone"
                                           value="<?php echo esc_attr($phone); ?>"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>

                                <!-- Address -->
                                <div class="md:col-span-2">
                                    <label for="address" class="block text-sm font-medium mb-2">
                                        <?php _e('Address', 'ckl-car-rental'); ?>
                                    </label>
                                    <input type="text"
                                           id="address"
                                           name="address"
                                           value="<?php echo esc_attr($address); ?>"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>

                                <!-- City -->
                                <div>
                                    <label for="city" class="block text-sm font-medium mb-2">
                                        <?php _e('City', 'ckl-car-rental'); ?>
                                    </label>
                                    <input type="text"
                                           id="city"
                                           name="city"
                                           value="<?php echo esc_attr($city); ?>"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>

                                <!-- State -->
                                <div>
                                    <label for="state" class="block text-sm font-medium mb-2">
                                        <?php _e('State', 'ckl-car-rental'); ?>
                                    </label>
                                    <input type="text"
                                           id="state"
                                           name="state"
                                           value="<?php echo esc_attr($state); ?>"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>

                                <!-- Postcode -->
                                <div>
                                    <label for="postcode" class="block text-sm font-medium mb-2">
                                        <?php _e('Postcode', 'ckl-car-rental'); ?>
                                    </label>
                                    <input type="text"
                                           id="postcode"
                                           name="postcode"
                                           value="<?php echo esc_attr($postcode); ?>"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>

                                <!-- License Number -->
                                <div>
                                    <label for="license_number" class="block text-sm font-medium mb-2">
                                        <?php _e('Driving License Number', 'ckl-car-rental'); ?>
                                    </label>
                                    <input type="text"
                                           id="license_number"
                                           name="license_number"
                                           value="<?php echo esc_attr($license_number); ?>"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                            </div>

                            <div class="mt-6">
                                <button type="submit"
                                        class="bg-primary text-white px-6 py-2 rounded-lg font-semibold hover:bg-primary/90 transition">
                                    <?php _e('Save Changes', 'ckl-car-rental'); ?>
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Change Password -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-xl font-bold mb-6 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                            <?php _e('Change Password', 'ckl-car-rental'); ?>
                        </h2>

                        <form method="post" action="">
                            <?php wp_nonce_field('ckl_update_profile', 'ckl_profile_nonce'); ?>
                            <input type="hidden" name="action" value="change_password">

                            <div class="space-y-4">
                                <!-- Current Password -->
                                <div>
                                    <label for="current_password" class="block text-sm font-medium mb-2">
                                        <?php _e('Current Password', 'ckl-car-rental'); ?>
                                    </label>
                                    <input type="password"
                                           id="current_password"
                                           name="current_password"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>

                                <!-- New Password -->
                                <div>
                                    <label for="new_password" class="block text-sm font-medium mb-2">
                                        <?php _e('New Password', 'ckl-car-rental'); ?>
                                    </label>
                                    <input type="password"
                                           id="new_password"
                                           name="new_password"
                                           minlength="8"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <p class="text-xs text-gray-500 mt-1">
                                        <?php _e('Minimum 8 characters', 'ckl-car-rental'); ?>
                                    </p>
                                </div>

                                <!-- Confirm New Password -->
                                <div>
                                    <label for="confirm_password" class="block text-sm font-medium mb-2">
                                        <?php _e('Confirm New Password', 'ckl-car-rental'); ?>
                                    </label>
                                    <input type="password"
                                           id="confirm_password"
                                           name="confirm_password"
                                           minlength="8"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                            </div>

                            <div class="mt-6">
                                <button type="submit"
                                        class="bg-primary text-white px-6 py-2 rounded-lg font-semibold hover:bg-primary/90 transition">
                                    <?php _e('Change Password', 'ckl-car-rental'); ?>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Password confirmation validation
    const newPassword = document.getElementById('new_password');
    const confirmPassword = document.getElementById('confirm_password');

    if (newPassword && confirmPassword) {
        confirmPassword.addEventListener('input', function() {
            if (this.value !== newPassword.value) {
                this.setCustomValidity('<?php _e('Passwords do not match', 'ckl-car-rental'); ?>');
            } else {
                this.setCustomValidity('');
            }
        });

        newPassword.addEventListener('input', function() {
            if (confirmPassword.value && confirmPassword.value !== this.value) {
                confirmPassword.setCustomValidity('<?php _e('Passwords do not match', 'ckl-car-rental'); ?>');
            } else {
                confirmPassword.setCustomValidity('');
            }
        });
    }
});
</script>

<?php get_footer(); ?>
