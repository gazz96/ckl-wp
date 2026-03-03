<!-- Footer -->
<footer class="bg-gray-900 text-gray-300">
    <div class="container mx-auto px-4 py-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">

            <!-- Logo & Description -->
            <div>
                <?php
                if (has_custom_logo()) {
                    $custom_logo_id = get_theme_mod('custom_logo');
                    $logo = wp_get_attachment_image_src($custom_logo_id, 'full');
                    echo '<img src="' . esc_url($logo[0]) . '" alt="' . esc_attr(get_bloginfo('name')) . '" class="h-16 w-auto mb-4 brightness-0 invert">';
                } else {
                    echo '<h3 class="text-white font-bold text-xl mb-4">' . esc_html(get_bloginfo('name')) . '</h3>';
                }
                ?>
                <p class="text-sm leading-relaxed">
                    <?php esc_html_e('Langkawi Vehicle Rentals Made Easy. Reliable Car, Bike, Van & Bus Rentals in Langkawi.', 'ckl-car-rental'); ?>
                </p>
            </div>

            <!-- Quick Links -->
            <div>
                <h3 class="text-white font-bold mb-4"><?php esc_html_e('Quick Links', 'ckl-car-rental'); ?></h3>
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'footer',
                    'container' => false,
                    'menu_class' => 'space-y-2',
                    'fallback_cb' => 'ckl_default_footer_menu',
                ));
                ?>
            </div>

            <!-- Helpful Links -->
            <div>
                <h3 class="text-white font-bold mb-4"><?php esc_html_e('Helpful Links', 'ckl-car-rental'); ?></h3>
                <ul class="space-y-2">
                    <li>
                        <a href="<?php echo esc_url(home_url('/reviews/')); ?>" class="hover:text-white transition">
                            <?php esc_html_e('Reviews', 'ckl-car-rental'); ?>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo esc_url(home_url('/bookmarks/')); ?>" class="hover:text-white transition">
                            <?php esc_html_e('Bookmarks', 'ckl-car-rental'); ?>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo esc_url(home_url('/bookings/')); ?>" class="hover:text-white transition">
                            <?php esc_html_e('Bookings', 'ckl-car-rental'); ?>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo esc_url(home_url('/profile/')); ?>" class="hover:text-white transition">
                            <?php esc_html_e('My Profile', 'ckl-car-rental'); ?>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo esc_url(home_url('/blog/')); ?>" class="hover:text-white transition">
                            <?php esc_html_e('Blog', 'ckl-car-rental'); ?>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Contact Info -->
            <div>
                <h3 class="text-white font-bold mb-4"><?php esc_html_e('Contact Us', 'ckl-car-rental'); ?></h3>
                <address class="not-italic text-sm space-y-2">
                    <p><strong><?php esc_html_e('CK LANGKAWI TRIP (002800247-T)', 'ckl-car-rental'); ?></strong></p>
                    <p><?php esc_html_e('Lot Kedai No.3,', 'ckl-car-rental'); ?></p>
                    <p><?php esc_html_e('Masjid Al-Aman Yooi,', 'ckl-car-rental'); ?></p>
                    <p><?php esc_html_e('Mukim Bohor,', 'ckl-car-rental'); ?></p>
                    <p>07000 <?php esc_html_e('Langkawi, Kedah Darul Aman.', 'ckl-car-rental'); ?></p>
                    <p>
                        <a href="mailto:[email protected]" class="hover:text-white transition">
                            <?php esc_html_e('E-Mail:', 'ckl-car-rental'); ?> [email protected]
                        </a>
                    </p>
                </address>
            </div>

        </div>
    </div>

    <!-- Copyright Bar -->
    <div class="bg-gray-950 py-4">
        <div class="container mx-auto px-4 text-center text-sm">
            <p>&copy; <?php echo date('Y'); ?> <?php echo esc_html(get_bloginfo('name')); ?>. <?php esc_html_e('All Rights Reserved.', 'ckl-car-rental'); ?></p>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>

</body>
</html>
