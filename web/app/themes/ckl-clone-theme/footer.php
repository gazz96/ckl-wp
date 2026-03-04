<!-- Footer -->
<footer class="bg-gray-50 border-t border-gray-200 py-12">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">

            <!-- Logo & Description -->
            <div>
                <img src="https://storage.baharihari.com/bahari/ck-langkawi/Pasted%20image%20(23).png"
                     alt="CK Langkawi"
                     class="h-24 w-auto object-contain mb-4">
                <p class="text-sm leading-relaxed text-gray-600">
                    <?php esc_html_e('Langkawi Vehicle Rentals Made Easy. Reliable Car, Bike, Van & Bus Rentals in Langkawi.', 'ckl-car-rental'); ?>
                </p>
            </div>

            <!-- Get Started -->
            <div>
                <h3 class="text-gray-900 font-bold mb-4"><?php esc_html_e('Get Started', 'ckl-car-rental'); ?></h3>
                <ul class="space-y-2">
                    <li>
                        <a href="<?php echo esc_url(get_post_type_archive_link('vehicle')); ?>" class="text-gray-600 hover:text-gray-900 transition">
                            <?php esc_html_e('Browse Cars', 'ckl-car-rental'); ?>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo esc_url(home_url('/how-to-book/')); ?>" class="text-gray-600 hover:text-gray-900 transition">
                            <?php esc_html_e('How To Book', 'ckl-car-rental'); ?>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo esc_url(home_url('/about/')); ?>" class="text-gray-600 hover:text-gray-900 transition">
                            <?php esc_html_e('About Us', 'ckl-car-rental'); ?>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Support -->
            <div>
                <h3 class="text-gray-900 font-bold mb-4"><?php esc_html_e('Support', 'ckl-car-rental'); ?></h3>
                <ul class="space-y-2">
                    <li>
                        <a href="<?php echo esc_url(home_url('/faq/')); ?>" class="text-gray-600 hover:text-gray-900 transition">
                            <?php esc_html_e('FAQ', 'ckl-car-rental'); ?>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo esc_url(home_url('/contact/')); ?>" class="text-gray-600 hover:text-gray-900 transition">
                            <?php esc_html_e('Contact Us', 'ckl-car-rental'); ?>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo esc_url(home_url('/terms-conditions/')); ?>" class="text-gray-600 hover:text-gray-900 transition">
                            <?php esc_html_e('Terms & Conditions', 'ckl-car-rental'); ?>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Contact -->
            <div>
                <h3 class="text-gray-900 font-bold mb-4"><?php esc_html_e('Contact', 'ckl-car-rental'); ?></h3>
                <ul class="space-y-3">
                    <li class="flex items-center gap-2 text-gray-600">
                        <svg class="h-4 w-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 0 1 2-2h3.28a1 1 0 0 1 .948.684l1.498 4.493a1 1 0 0 1-.502 1.21l-2.257 1.13a11.042 11.042 0 0 0 5.516 5.516l1.13-2.257a1 1 0 0 1 1.21-.502l4.493 1.498a1 1 0 0 1 .684.949V19a2 2 0 0 1-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        <span>+60 194 428 040</span>
                    </li>
                    <li class="flex items-center gap-2 text-gray-600">
                        <svg class="h-4 w-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 0 0 2.22 0L21 8M5 19h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2z"/>
                        </svg>
                        <a href="mailto:[email protected]" class="hover:text-gray-900 transition">
                            [email protected]
                        </a>
                    </li>
                    <li class="flex items-start gap-2 text-gray-600">
                        <svg class="h-4 w-4 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 0 1-2.827 0l-4.244-4.243a8 8 0 1 1 11.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
                        </svg>
                        <span>
                            <?php esc_html_e('Lot Kedai No 3, Masjid Al-Aman Yooi, Jalan Padang Matsirat, 07000 Langkawi, Kedah, Malaysia', 'ckl-car-rental'); ?>
                        </span>
                    </li>
                </ul>

                <!-- Social Links -->
                <div class="flex gap-4 mt-4">
                    <a href="https://facebook.com/cklangkawi" target="_blank" rel="noopener noreferrer" class="text-gray-600 hover:text-gray-900 transition">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                    </a>
                    <a href="https://instagram.com/cklangkawi" target="_blank" rel="noopener noreferrer" class="text-gray-600 hover:text-gray-900 transition">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                        </svg>
                    </a>
                </div>
            </div>

        </div>
    </div>

    <!-- Copyright Bar -->
    <div class="border-t border-gray-200 mt-8 pt-8">
        <div class="container mx-auto px-4 text-center text-sm text-gray-600">
            <p>© 2026 CK Langkawi. All rights reserved.</p>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>

</body>
</html>
