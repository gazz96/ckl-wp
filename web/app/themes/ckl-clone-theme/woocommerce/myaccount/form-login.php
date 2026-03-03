<?php
/**
 * My Account - Login/Registration Form
 *
 * Custom login/registration template with modern card-based design
 *
 * @package WooCommerce/Templates
 * @version 9.6.0
 */

defined('ABSPATH') || exit;

// Check if registration is enabled
$registration_enabled = get_option('woocommerce_enable_myaccount_registration') === 'yes';

get_header();
?>

<div class="min-h-screen bg-gray-50 flex items-center justify-center px-4 py-12">
    <div class="w-full <?php echo $registration_enabled ? 'max-w-5xl' : 'max-w-md'; ?> animate-fade-in">
        <?php
        /**
         * Hook: woocommerce_before_customer_login_form
         *
         * @hooked WC_Print_Form_In::print_login_form_notice - 10
         */
        do_action('woocommerce_before_customer_login_form');
        ?>

        <?php if ($registration_enabled) : ?>
        <!-- Two Column Layout: Login + Registration -->
        <div class="grid md:grid-cols-2 gap-6">
        <?php endif; ?>

            <!-- Login Card -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
                <!-- Logo/Brand Section -->
                <div class="text-center mb-8">
                    <?php
                    $custom_logo_id = get_theme_mod('custom_logo');
                    $logo = wp_get_attachment_image_src($custom_logo_id, 'full');
                    if (has_custom_logo()) :
                    ?>
                        <img src="<?php echo esc_url($logo[0]); ?>" alt="<?php echo esc_attr(get_bloginfo('name')); ?>" class="h-12 mx-auto mb-4">
                    <?php else : ?>
                        <h1 class="text-3xl font-bold text-[#cc2e28]"><?php echo esc_html(get_bloginfo('name')); ?></h1>
                    <?php endif; ?>
                    <h2 class="text-2xl font-bold text-gray-900">Welcome Back</h2>
                    <p class="text-gray-600 mt-2">Sign in to access your account</p>
                </div>

                <!-- Login Form -->
                <form method="post" class="woocommerce-form woocommerce-form-login login" <?php do_action('woocommerce_login_form_tag_attrs'); ?>>

                    <?php do_action('woocommerce_login_form_start'); ?>

                    <!-- Username/Email Field -->
                    <div class="mb-5">
                        <label for="username" class="block text-sm font-semibold text-gray-700 mb-2">
                            <?php esc_html_e('Username or email', 'woocommerce'); ?>&nbsp;<span class="text-[#cc2e28]">*</span>
                        </label>
                        <input type="text"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#cc2e28] focus:border-[#cc2e28] transition-all duration-200 outline-none"
                               name="username"
                               id="username"
                               autocomplete="username"
                               required
                               aria-required="true"
                               placeholder="<?php esc_attr_e('Enter your username or email', 'woocommerce'); ?>" />
                    </div>

                    <!-- Password Field -->
                    <div class="mb-5">
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                            <?php esc_html_e('Password', 'woocommerce'); ?>&nbsp;<span class="text-[#cc2e28]">*</span>
                        </label>
                        <input class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#cc2e28] focus:border-[#cc2e28] transition-all duration-200 outline-none"
                               type="password"
                               name="password"
                               id="password"
                               autocomplete="current-password"
                               required
                               aria-required="true"
                               placeholder="<?php esc_attr_e('Enter your password', 'woocommerce'); ?>" />
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
                            class="w-full bg-[#cc2e28] text-white px-6 py-3 rounded-lg font-semibold hover:bg-[#a8241f] focus:ring-4 focus:ring-[#cc2e28]/20 transition-all duration-200 transform hover:-translate-y-0.5 active:translate-y-0"
                            name="login"
                            value="<?php esc_attr_e('Log in', 'woocommerce'); ?>">
                        <?php esc_html_e('Log in', 'woocommerce'); ?>
                    </button>

                    <?php do_action('woocommerce_login_form_end'); ?>

                </form>
            </div>

            <?php if ($registration_enabled) : ?>
            <!-- Registration Card -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
                <!-- Logo/Brand Section -->
                <div class="text-center mb-8">
                    <?php
                    if (has_custom_logo()) :
                    ?>
                        <img src="<?php echo esc_url($logo[0]); ?>" alt="<?php echo esc_attr(get_bloginfo('name')); ?>" class="h-12 mx-auto mb-4">
                    <?php else : ?>
                        <h1 class="text-3xl font-bold text-[#cc2e28]"><?php echo esc_html(get_bloginfo('name')); ?></h1>
                    <?php endif; ?>
                    <h2 class="text-2xl font-bold text-gray-900">Create Account</h2>
                    <p class="text-gray-600 mt-2">Register to start booking</p>
                </div>

                <!-- Registration Form -->
                <form method="post" class="woocommerce-form woocommerce-form-register register" <?php do_action('woocommerce_register_form_tag_attrs'); ?>>

                    <?php do_action('woocommerce_register_form_start'); ?>

                    <?php if ('no' === get_option('woocommerce_registration_generate_username')) : ?>
                        <!-- Username Field -->
                        <div class="mb-5">
                            <label for="reg_username" class="block text-sm font-semibold text-gray-700 mb-2">
                                <?php esc_html_e('Username', 'woocommerce'); ?>&nbsp;<span class="text-[#cc2e28]">*</span>
                            </label>
                            <input type="text"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#cc2e28] focus:border-[#cc2e28] transition-all duration-200 outline-none"
                                   name="username"
                                   id="reg_username"
                                   autocomplete="username"
                                   required
                                   aria-required="true"
                                   placeholder="<?php esc_attr_e('Choose a username', 'woocommerce'); ?>" />
                        </div>
                    <?php endif; ?>

                    <!-- Email Field -->
                    <div class="mb-5">
                        <label for="reg_email" class="block text-sm font-semibold text-gray-700 mb-2">
                            <?php esc_html_e('Email address', 'woocommerce'); ?>&nbsp;<span class="text-[#cc2e28]">*</span>
                        </label>
                        <input type="email"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#cc2e28] focus:border-[#cc2e28] transition-all duration-200 outline-none"
                               name="email"
                               id="reg_email"
                               autocomplete="email"
                               required
                               aria-required="true"
                               placeholder="<?php esc_attr_e('Enter your email address', 'woocommerce'); ?>" />
                    </div>

                    <?php if ('no' === get_option('woocommerce_registration_generate_password')) : ?>
                        <!-- Password Field -->
                        <div class="mb-5">
                            <label for="reg_password" class="block text-sm font-semibold text-gray-700 mb-2">
                                <?php esc_html_e('Password', 'woocommerce'); ?>&nbsp;<span class="text-[#cc2e28]">*</span>
                            </label>
                            <input type="password"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#cc2e28] focus:border-[#cc2e28] transition-all duration-200 outline-none"
                                   name="password"
                                   id="reg_password"
                                   autocomplete="new-password"
                                   required
                                   aria-required="true"
                                   placeholder="<?php esc_attr_e('Create a password', 'woocommerce'); ?>" />
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
                            class="w-full bg-[#cc2e28] text-white px-6 py-3 rounded-lg font-semibold hover:bg-[#a8241f] focus:ring-4 focus:ring-[#cc2e28]/20 transition-all duration-200 transform hover:-translate-y-0.5 active:translate-y-0"
                            name="register"
                            value="<?php esc_attr_e('Register', 'woocommerce'); ?>">
                        <?php esc_html_e('Register', 'woocommerce'); ?>
                    </button>

                    <?php do_action('woocommerce_register_form_end'); ?>

                </form>
            </div>
            <?php endif; ?>

        <?php if ($registration_enabled) : ?>
        </div>
        <?php endif; ?>

        <?php
        /**
         * Hook: woocommerce_after_customer_login_form
         */
        do_action('woocommerce_after_customer_login_form');
        ?>
    </div>
</div>

<?php get_footer(); ?>
