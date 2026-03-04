<?php
/**
 * Template Name: Auth Page (Login/Register)
 *
 * Custom authentication page with tabbed login/signup interface
 *
 * @package CKL_Car_Rental
 */

defined('ABSPATH') || exit;

// Redirect if already logged in
if (is_user_logged_in()) {
    $redirect_url = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('myaccount') : home_url('/');
    wp_safe_redirect($redirect_url);
    exit;
}

get_header();

// Check if registration is enabled
$registration_enabled = get_option('woocommerce_enable_myaccount_registration') === 'yes';
?>

<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-md animate-fade-in">
        <?php
        /**
         * Hook: woocommerce_before_customer_login_form
         */
        do_action('woocommerce_before_customer_login_form');
        ?>

        <!-- Auth Card -->
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
            <!-- Logo Section -->
            <div class="bg-gradient-to-r from-[#cc2e28] to-[#a8241f] p-8 text-center">
                <?php
                $custom_logo_id = get_theme_mod('custom_logo');
                $logo = wp_get_attachment_image_src($custom_logo_id, 'full');
                if (has_custom_logo()) :
                ?>
                    <img src="<?php echo esc_url($logo[0]); ?>" alt="<?php echo esc_attr(get_bloginfo('name')); ?>" class="h-16 mx-auto mb-3 drop-shadow-lg">
                <?php else : ?>
                    <h1 class="text-3xl font-bold text-white"><?php echo esc_html(get_bloginfo('name')); ?></h1>
                <?php endif; ?>
                <p class="text-white/90 text-sm mt-2"><?php esc_html_e('Car Rental Services', 'ckl-car-rental'); ?></p>
            </div>

            <!-- Tabs Navigation -->
            <div class="flex border-b border-gray-200">
                <button type="button"
                        id="tab-signin"
                        class="auth-tab flex-1 py-4 text-center font-semibold text-sm uppercase tracking-wider transition-all duration-300 border-b-2 border-[#cc2e28] text-[#cc2e28]">
                    <?php esc_html_e('Sign In', 'ckl-car-rental'); ?>
                </button>
                <?php if ($registration_enabled) : ?>
                <button type="button"
                        id="tab-signup"
                        class="auth-tab flex-1 py-4 text-center font-semibold text-sm uppercase tracking-wider transition-all duration-300 border-b-2 border-transparent text-gray-500 hover:text-gray-700">
                    <?php esc_html_e('Sign Up', 'ckl-car-rental'); ?>
                </button>
                <?php endif; ?>
            </div>

            <!-- Forms Container -->
            <div class="p-8">
                <!-- Sign In Form -->
                <div id="form-signin" class="auth-form">
                    <form method="post" class="woocommerce-form woocommerce-form-login login" <?php do_action('woocommerce_login_form_tag_attrs'); ?>>

                        <?php do_action('woocommerce_login_form_start'); ?>

                        <!-- Username/Email Field -->
                        <div class="mb-5">
                            <label for="username" class="block text-sm font-semibold text-gray-700 mb-2">
                                <?php esc_html_e('Username or email', 'woocommerce'); ?>&nbsp;<span class="text-[#cc2e28]">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </span>
                                <input type="text"
                                       class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#cc2e28] focus:border-[#cc2e28] transition-all duration-200 outline-none"
                                       name="username"
                                       id="username"
                                       autocomplete="username"
                                       required
                                       aria-required="true"
                                       placeholder="<?php esc_attr_e('Enter your username or email', 'woocommerce'); ?>" />
                            </div>
                        </div>

                        <!-- Password Field -->
                        <div class="mb-5">
                            <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                                <?php esc_html_e('Password', 'woocommerce'); ?>&nbsp;<span class="text-[#cc2e28]">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                </span>
                                <input class="w-full pl-10 pr-12 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#cc2e28] focus:border-[#cc2e28] transition-all duration-200 outline-none"
                                       type="password"
                                       name="password"
                                       id="password"
                                       autocomplete="current-password"
                                       required
                                       aria-required="true"
                                       placeholder="<?php esc_attr_e('Enter your password', 'woocommerce'); ?>" />
                                <button type="button"
                                        id="toggle-password"
                                        class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <?php do_action('woocommerce_login_form'); ?>

                        <!-- Remember Me & Lost Password -->
                        <div class="flex items-center justify-between mb-6">
                            <label class="flex items-center cursor-pointer">
                                <input class="woocommerce-form__input woocommerce-form__input-checkbox w-4 h-4 text-[#cc2e28] border-gray-300 rounded focus:ring-2 focus:ring-[#cc2e28] focus:ring-offset-2"
                                       name="rememberme"
                                       type="checkbox"
                                       id="rememberme"
                                       value="forever" />
                                <span class="ml-2 text-sm text-gray-600"><?php esc_html_e('Remember me', 'woocommerce'); ?></span>
                            </label>
                            <a href="<?php echo esc_url(wc_lostpassword_url()); ?>" class="text-sm text-[#cc2e28] hover:text-[#a8241f] font-medium transition-colors duration-200">
                                <?php esc_html_e('Lost password?', 'woocommerce'); ?>
                            </a>
                        </div>

                        <!-- Login Button -->
                        <?php wp_nonce_field('woocommerce-login', 'woocommerce-login-nonce'); ?>
                        <button type="submit"
                                class="w-full bg-gradient-to-r from-[#cc2e28] to-[#a8241f] text-white px-6 py-3 rounded-lg font-semibold hover:shadow-lg focus:ring-4 focus:ring-[#cc2e28]/20 transition-all duration-200 transform hover:-translate-y-0.5 active:translate-y-0"
                                name="login"
                                value="<?php esc_attr_e('Log in', 'woocommerce'); ?>">
                            <?php esc_html_e('Sign In', 'woocommerce'); ?>
                        </button>

                        <?php do_action('woocommerce_login_form_end'); ?>

                    </form>
                </div>

                <?php if ($registration_enabled) : ?>
                <!-- Sign Up Form -->
                <div id="form-signup" class="auth-form hidden">
                    <form method="post" class="woocommerce-form woocommerce-form-register register" <?php do_action('woocommerce_register_form_tag_attrs'); ?>>

                        <?php do_action('woocommerce_register_form_start'); ?>

                        <?php if ('no' === get_option('woocommerce_registration_generate_username')) : ?>
                        <!-- Username Field -->
                        <div class="mb-5">
                            <label for="reg_username" class="block text-sm font-semibold text-gray-700 mb-2">
                                <?php esc_html_e('Username', 'woocommerce'); ?>&nbsp;<span class="text-[#cc2e28]">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </span>
                                <input type="text"
                                       class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#cc2e28] focus:border-[#cc2e28] transition-all duration-200 outline-none"
                                       name="username"
                                       id="reg_username"
                                       autocomplete="username"
                                       required
                                       aria-required="true"
                                       placeholder="<?php esc_attr_e('Choose a username', 'woocommerce'); ?>" />
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- Email Field -->
                        <div class="mb-5">
                            <label for="reg_email" class="block text-sm font-semibold text-gray-700 mb-2">
                                <?php esc_html_e('Email address', 'woocommerce'); ?>&nbsp;<span class="text-[#cc2e28]">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                </span>
                                <input type="email"
                                       class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#cc2e28] focus:border-[#cc2e28] transition-all duration-200 outline-none"
                                       name="email"
                                       id="reg_email"
                                       autocomplete="email"
                                       required
                                       aria-required="true"
                                       placeholder="<?php esc_attr_e('Enter your email address', 'woocommerce'); ?>" />
                            </div>
                        </div>

                        <?php if ('no' === get_option('woocommerce_registration_generate_password')) : ?>
                        <!-- Password Field -->
                        <div class="mb-5">
                            <label for="reg_password" class="block text-sm font-semibold text-gray-700 mb-2">
                                <?php esc_html_e('Password', 'woocommerce'); ?>&nbsp;<span class="text-[#cc2e28]">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                </span>
                                <input type="password"
                                       class="w-full pl-10 pr-12 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#cc2e28] focus:border-[#cc2e28] transition-all duration-200 outline-none"
                                       name="password"
                                       id="reg_password"
                                       autocomplete="new-password"
                                       required
                                       aria-required="true"
                                       placeholder="<?php esc_attr_e('Create a password', 'woocommerce'); ?>" />
                                <button type="button"
                                        id="toggle-reg-password"
                                        class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php do_action('woocommerce_register_form'); ?>

                        <!-- Privacy Policy (if enabled) -->
                        <?php
                        $privacy_policy_url = get_privacy_policy_url();
                        if ($privacy_policy_url && wc_get_privacy_policy_text('registration')) :
                        ?>
                        <div class="mb-6">
                            <label class="flex items-start cursor-pointer">
                                <input class="woocommerce-form__input woocommerce-form__input-checkbox w-4 h-4 mt-0.5 text-[#cc2e28] border-gray-300 rounded focus:ring-2 focus:ring-[#cc2e28] focus:ring-offset-2"
                                       type="checkbox"
                                       name="privacy-policy"
                                       id="privacy-policy"
                                       required
                                       aria-required="true" />
                                <span class="ml-2 text-sm text-gray-600">
                                    <?php echo wp_kses_post(wc_get_privacy_policy_text('registration')); ?>
                                </span>
                            </label>
                        </div>
                        <?php endif; ?>

                        <!-- Register Button -->
                        <?php wp_nonce_field('woocommerce-register', 'woocommerce-register-nonce'); ?>
                        <button type="submit"
                                class="w-full bg-gradient-to-r from-[#cc2e28] to-[#a8241f] text-white px-6 py-3 rounded-lg font-semibold hover:shadow-lg focus:ring-4 focus:ring-[#cc2e28]/20 transition-all duration-200 transform hover:-translate-y-0.5 active:translate-y-0"
                                name="register"
                                value="<?php esc_attr_e('Register', 'woocommerce'); ?>">
                            <?php esc_html_e('Create Account', 'woocommerce'); ?>
                        </button>

                        <?php do_action('woocommerce_register_form_end'); ?>

                    </form>
                </div>
                <?php endif; ?>
            </div>

            <!-- Footer -->
            <div class="bg-gray-50 px-8 py-4 border-t border-gray-200">
                <p class="text-center text-sm text-gray-500">
                    <?php esc_html_e('By continuing, you agree to our Terms of Service and Privacy Policy', 'ckl-car-rental'); ?>
                </p>
            </div>
        </div>

        <?php
        /**
         * Hook: woocommerce_after_customer_login_form
         */
        do_action('woocommerce_after_customer_login_form');
        ?>
    </div>
</div>

<!-- Auth Page Script -->
<script>
(function() {
    // Tab switching functionality
    const tabs = document.querySelectorAll('.auth-tab');
    const forms = document.querySelectorAll('.auth-form');

    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const targetId = this.id.replace('tab-', 'form-');

            // Update tab styles
            tabs.forEach(t => {
                t.classList.remove('border-[#cc2e28]', 'text-[#cc2e28]');
                t.classList.add('border-transparent', 'text-gray-500');
            });
            this.classList.remove('border-transparent', 'text-gray-500');
            this.classList.add('border-[#cc2e28]', 'text-[#cc2e28]');

            // Show/hide forms with animation
            forms.forEach(form => {
                if (form.id === targetId) {
                    form.classList.remove('hidden');
                    form.classList.add('animate-fade-in');
                } else {
                    form.classList.add('hidden');
                    form.classList.remove('animate-fade-in');
                }
            });
        });
    });

    // Password toggle functionality
    const togglePassword = document.getElementById('toggle-password');
    const passwordInput = document.getElementById('password');

    if (togglePassword && passwordInput) {
        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
        });
    }

    // Registration password toggle
    const toggleRegPassword = document.getElementById('toggle-reg-password');
    const regPasswordInput = document.getElementById('reg_password');

    if (toggleRegPassword && regPasswordInput) {
        toggleRegPassword.addEventListener('click', function() {
            const type = regPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            regPasswordInput.setAttribute('type', type);
        });
    }
})();
</script>

<style>
@keyframes fade-in {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fade-in 0.3s ease-out;
}
</style>

<?php get_footer(); ?>
