<?php
/**
 * Email Footer
 *
 * Override WooCommerce email footer with custom branding.
 *
 * @package CKL_Car_Rental
 */

if (!defined('ABSPATH')) {
    exit;
}
?>
        </div>
        <div id="footer">
            <p><?php printf(__('Thank you for choosing %s!', 'ckl-car-rental'), esc_html(get_bloginfo('name'))); ?></p>
            <p><?php _e('CK LANGKAWI TRIP (002800247-T)', 'ckl-car-rental'); ?></p>
            <p><?php _e('Lot Kedai No.3, Masjid Al-Aman Yooi, Mukim Bohor, 07000 Langkawi, Kedah Darul Aman.', 'ckl-car-rental'); ?></p>
            <p>
                <a href="mailto:[email protected]">[email protected]</a> |
                <a href="<?php echo esc_url(home_url('/')); ?>"><?php echo esc_html(home_url('/')); ?></a>
            </p>
            <p style="margin-top: 20px;">
                <?php
                /* translators: %s: Site title */
                printf(__('<a href="%s">Unsubscribe</a>', 'ckl-car-rental'), '{unsubscribe_url}');
                ?>
            </p>
        </div>
    </div>
</body>
</html>
