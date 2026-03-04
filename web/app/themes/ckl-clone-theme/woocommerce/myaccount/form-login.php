<?php
/**
 * My Account - Login/Registration Form
 *
 * Custom login/registration template with tabbed interface
 * Design matches cklangkawi.com/auth reference
 *
 * @package WooCommerce/Templates
 * @version 9.6.0
 */

defined('ABSPATH') || exit;

// Check if registration is enabled
$registration_enabled = get_option('woocommerce_enable_myaccount_registration') === 'yes';

get_header();
?>

<div class="min-h-screen flex items-center justify-center bg-muted/30 p-4">
    <div class="w-full max-w-md">
        <?php
        /**
         * Hook: woocommerce_before_customer_login_form
         */
        do_action('woocommerce_before_customer_login_form');
        ?>

        <!-- Logo Section -->
        <div class="text-center mb-8">
            <a class="inline-flex justify-center mb-4" href="/">
                <?php
                $custom_logo_id = get_theme_mod('custom_logo');
                $logo = wp_get_attachment_image_src($custom_logo_id, 'full');
                if (has_custom_logo()) :
                ?>
                    <img src="<?php echo esc_url($logo[0]); ?>" alt="<?php echo esc_attr(get_bloginfo('name')); ?>" class="h-20 w-auto object-contain">
                <?php else : ?>
                    <h1 class="text-3xl font-bold text-foreground"><?php echo esc_html(get_bloginfo('name')); ?></h1>
                <?php endif; ?>
            </a>
            <p class="text-muted-foreground"><?php esc_html_e('Start your journey with us', 'ckl-car-rental'); ?></p>
        </div>

        <!-- Tab Navigation -->
        <div class="w-full">
            <div role="tablist" class="h-10 items-center justify-center rounded-md bg-muted p-1 text-muted-foreground grid w-full grid-cols-2">
                <button type="button"
                        role="tab"
                        data-state="active"
                        id="tab-signin"
                        class="auth-tab"
                        aria-selected="true"
                        aria-controls="panel-signin">
                    <?php esc_html_e('Sign In', 'ckl-car-rental'); ?>
                </button>
                <?php if ($registration_enabled) : ?>
                <button type="button"
                        role="tab"
                        data-state="inactive"
                        id="tab-signup"
                        class="auth-tab"
                        aria-selected="false"
                        aria-controls="panel-signup">
                    <?php esc_html_e('Sign Up', 'ckl-car-rental'); ?>
                </button>
                <?php endif; ?>
            </div>

            <!-- Sign In Panel -->
            <div role="tabpanel"
                 data-state="active"
                 id="panel-signin"
                 tabindex="0"
                 aria-labelledby="tab-signin">
                <div class="rounded-lg border bg-card text-card-foreground shadow-sm mt-2">
                    <div class="flex flex-col space-y-1.5 p-6">
                        <h3 class="text-2xl font-semibold leading-none tracking-tight">
                            <?php esc_html_e('Welcome Back', 'ckl-car-rental'); ?>
                        </h3>
                        <p class="text-sm text-muted-foreground">
                            <?php esc_html_e('Sign in to access your bookings and messages', 'ckl-car-rental'); ?>
                        </p>
                    </div>
                    <div class="p-6 pt-0">
                        <form method="post" class="woocommerce-form woocommerce-form-login login space-y-4" <?php do_action('woocommerce_login_form_tag_attrs'); ?>>

                            <?php do_action('woocommerce_login_form_start'); ?>

                            <!-- Username/Email Field -->
                            <div class="space-y-2">
                                <label for="username" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                                    <?php esc_html_e('Username or email', 'woocommerce'); ?>
                                    <span class="text-destructive">*</span>
                                </label>
                                <input type="text"
                                       class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                                       name="username"
                                       id="username"
                                       autocomplete="username"
                                       required
                                       aria-required="true"
                                       placeholder="<?php esc_attr_e('Enter your username or email', 'woocommerce'); ?>" />
                            </div>

                            <!-- Password Field -->
                            <div class="space-y-2">
                                <label for="password" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                                    <?php esc_html_e('Password', 'woocommerce'); ?>
                                    <span class="text-destructive">*</span>
                                </label>
                                <div class="relative">
                                    <input type="password"
                                           class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 pr-10"
                                           name="password"
                                           id="password"
                                           autocomplete="current-password"
                                           required
                                           aria-required="true"
                                           placeholder="<?php esc_attr_e('Enter your password', 'woocommerce'); ?>" />
                                    <button type="button"
                                            id="toggle-password"
                                            class="absolute right-3 top-1/2 -translate-y-1/2 text-muted-foreground hover:text-foreground transition-colors">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <?php do_action('woocommerce_login_form'); ?>

                            <!-- Remember Me & Lost Password -->
                            <div class="flex items-center justify-between">
                                <label class="flex items-center space-x-2 text-sm">
                                    <input class="woocommerce-form__input woocommerce-form__input-checkbox h-4 w-4 rounded border-input ring-offset-background focus-visible:ring-2 focus-visible:ring-ring"
                                           name="rememberme"
                                           type="checkbox"
                                           id="rememberme"
                                           value="forever" />
                                    <span><?php esc_html_e('Remember me', 'woocommerce'); ?></span>
                                </label>
                                <a href="<?php echo esc_url(wc_lostpassword_url()); ?>" class="text-sm text-primary hover:underline">
                                    <?php esc_html_e('Lost password?', 'woocommerce'); ?>
                                </a>
                            </div>

                            <?php wp_nonce_field('woocommerce-login', 'woocommerce-login-nonce'); ?>
                            <button type="submit"
                                    class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none [&_svg]:size-4 [&_svg]:shrink-0 gradient-ocean text-primary-foreground hover:opacity-90 transition-all shadow-lg hover:shadow-xl h-10 px-4 py-2 w-full"
                                    name="login"
                                    value="<?php esc_attr_e('Log in', 'woocommerce'); ?>">
                                <?php esc_html_e('Sign In', 'woocommerce'); ?>
                            </button>

                            <?php do_action('woocommerce_login_form_end'); ?>

                        </form>
                    </div>
                </div>
            </div>

            <?php if ($registration_enabled) : ?>
            <!-- Sign Up Panel -->
            <div role="tabpanel"
                 data-state="inactive"
                 id="panel-signup"
                 tabindex="0"
                 aria-labelledby="tab-signup"
                 class="hidden">
                <div class="rounded-lg border bg-card text-card-foreground shadow-sm mt-2">
                    <div class="flex flex-col space-y-1.5 p-6">
                        <h3 class="text-2xl font-semibold leading-none tracking-tight">
                            <?php esc_html_e('Create Account', 'ckl-car-rental'); ?>
                        </h3>
                        <p class="text-sm text-muted-foreground">
                            <?php esc_html_e('Join us and start your booking experience', 'ckl-car-rental'); ?>
                        </p>
                    </div>
                    <div class="p-6 pt-0">
                        <form method="post" class="woocommerce-form woocommerce-form-register register space-y-4" <?php do_action('woocommerce_register_form_tag_attrs'); ?>>

                            <?php do_action('woocommerce_register_form_start'); ?>

                            <?php if ('no' === get_option('woocommerce_registration_generate_username')) : ?>
                            <!-- Username Field -->
                            <div class="space-y-2">
                                <label for="reg_username" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                                    <?php esc_html_e('Username', 'woocommerce'); ?>
                                    <span class="text-destructive">*</span>
                                </label>
                                <input type="text"
                                       class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                                       name="username"
                                       id="reg_username"
                                       autocomplete="username"
                                       required
                                       aria-required="true"
                                       placeholder="<?php esc_attr_e('Choose a username', 'woocommerce'); ?>" />
                            </div>
                            <?php endif; ?>

                            <!-- Email Field -->
                            <div class="space-y-2">
                                <label for="reg_email" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                                    <?php esc_html_e('Email address', 'woocommerce'); ?>
                                    <span class="text-destructive">*</span>
                                </label>
                                <input type="email"
                                       class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                                       name="email"
                                       id="reg_email"
                                       autocomplete="email"
                                       required
                                       aria-required="true"
                                       placeholder="<?php esc_attr_e('Enter your email address', 'woocommerce'); ?>" />
                            </div>

                            <?php if ('no' === get_option('woocommerce_registration_generate_password')) : ?>
                            <!-- Password Field -->
                            <div class="space-y-2">
                                <label for="reg_password" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                                    <?php esc_html_e('Password', 'woocommerce'); ?>
                                    <span class="text-destructive">*</span>
                                </label>
                                <div class="relative">
                                    <input type="password"
                                           class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 pr-10"
                                           name="password"
                                           id="reg_password"
                                           autocomplete="new-password"
                                           required
                                           aria-required="true"
                                           placeholder="<?php esc_attr_e('Create a password', 'woocommerce'); ?>" />
                                    <button type="button"
                                            id="toggle-reg-password"
                                            class="absolute right-3 top-1/2 -translate-y-1/2 text-muted-foreground hover:text-foreground transition-colors">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                            <div class="flex items-start space-x-2">
                                <input class="woocommerce-form__input woocommerce-form__input-checkbox h-4 w-4 mt-0.5 rounded border-input ring-offset-background focus-visible:ring-2 focus-visible:ring-ring"
                                       type="checkbox"
                                       name="privacy-policy"
                                       id="privacy-policy"
                                       required
                                       aria-required="true" />
                                <label for="privacy-policy" class="text-sm text-muted-foreground leading-tight">
                                    <?php echo wp_kses_post(wc_get_privacy_policy_text('registration')); ?>
                                </label>
                            </div>
                            <?php endif; ?>

                            <?php wp_nonce_field('woocommerce-register', 'woocommerce-register-nonce'); ?>
                            <button type="submit"
                                    class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none [&_svg]:size-4 [&_svg]:shrink-0 gradient-ocean text-primary-foreground hover:opacity-90 transition-all shadow-lg hover:shadow-xl h-10 px-4 py-2 w-full"
                                    name="register"
                                    value="<?php esc_attr_e('Register', 'woocommerce'); ?>">
                                <?php esc_html_e('Create Account', 'woocommerce'); ?>
                            </button>

                            <?php do_action('woocommerce_register_form_end'); ?>

                        </form>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Footer -->
        <p class="text-center text-sm text-muted-foreground mt-4">
            <?php esc_html_e('By continuing, you agree to our Terms of Service and Privacy Policy', 'ckl-car-rental'); ?>
        </p>

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
    const tabs = document.querySelectorAll('[role="tab"]');
    const panels = document.querySelectorAll('[role="tabpanel"]');

    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const targetId = this.getAttribute('aria-controls');

            // Update tab states
            tabs.forEach(t => {
                t.setAttribute('data-state', 'inactive');
                t.setAttribute('aria-selected', 'false');
            });
            this.setAttribute('data-state', 'active');
            this.setAttribute('aria-selected', 'true');

            // Update panel visibility
            panels.forEach(panel => {
                if (panel.id === targetId) {
                    panel.removeAttribute('hidden');
                    panel.setAttribute('data-state', 'active');
                } else {
                    panel.setAttribute('hidden', '');
                    panel.setAttribute('data-state', 'inactive');
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

<?php get_footer(); ?>
