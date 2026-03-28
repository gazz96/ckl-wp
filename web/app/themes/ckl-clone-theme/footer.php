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

<?php
// Load WhatsApp configuration
$whatsapp_config = require get_template_directory() . '/config/whatsapp.php';

// Only show WhatsApp button if enabled in config
if ($whatsapp_config['enabled']) :
    // Build WhatsApp URL with proper encoding
    $whatsapp_url = 'https://api.whatsapp.com/send/?phone=' . $whatsapp_config['phone'] . '&text=' . urlencode($whatsapp_config['message']) . '&type=phone_number&app_absent=0';
    
    // Determine position classes based on config
    $position_classes = 'fixed z-50 bg-green-500 hover:bg-green-600 text-white p-4 rounded-full shadow-lg transition-all duration-300 hover:scale-110 flex items-center justify-center group';
    
    switch ($whatsapp_config['position']) {
        case 'bottom-left':
            $position_classes .= ' bottom-6 left-6';
            break;
        case 'top-right':
            $position_classes .= ' top-6 right-6';
            break;
        case 'top-left':
            $position_classes .= ' top-6 left-6';
            break;
        case 'bottom-right':
        default:
            $position_classes .= ' bottom-6 right-6';
            break;
    }
?>
    <!-- Floating WhatsApp Button -->
    <a href="<?php echo esc_url($whatsapp_url); ?>" 
       target="_blank"
       rel="noopener noreferrer"
       class="<?php echo esc_attr($position_classes); ?>"
       aria-label="Contact us on WhatsApp">
        <!-- WhatsApp Icon -->
        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
        </svg>
        
        <!-- Tooltip -->
        <span class="absolute right-full mr-3 px-3 py-1 bg-white text-gray-800 text-sm font-medium rounded-lg shadow-lg whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none">
            <?php echo esc_html($whatsapp_config['tooltip']); ?>
        </span>
    </a>
<?php endif; ?>

</body>
</html>
