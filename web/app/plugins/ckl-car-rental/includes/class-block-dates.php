<?php
/**
 * CKL Block Dates
 *
 * Handles blocking dates for vehicles (maintenance, personal use, etc.)
 */

if (!defined('ABSPATH')) {
    exit;
}

class CKL_Block_Dates {

    /**
     * Initialize the class
     */
    public static function init() {
        add_action('init', array(__CLASS__, 'register_blocked_dates_cpt'));
        add_action('add_meta_boxes', array(__CLASS__, 'add_blocked_dates_meta_boxes'));
        add_action('save_post_blocked_date', array(__CLASS__, 'save_blocked_date_meta'));
        add_action('admin_post_ckl_bulk_block_dates', array(__CLASS__, 'handle_bulk_block_dates'));
        add_action('wp_ajax_ckl_get_blocked_dates', array(__CLASS__, 'ajax_get_blocked_dates'));
        add_filter('woocommerce_bookings_is_valid', array(__CLASS__, 'validate_booking_against_blocked_dates'), 10, 4);
    }

    /**
     * Register Blocked Dates Custom Post Type
     */
    public static function register_blocked_dates_cpt() {
        $labels = array(
            'name' => __('Blocked Dates', 'ckl-car-rental'),
            'singular_name' => __('Blocked Date', 'ckl-car-rental'),
            'menu_name' => __('Blocked Dates', 'ckl-car-rental'),
            'add_new' => __('Block Dates', 'ckl-car-rental'),
            'add_new_item' => __('Add New Blocked Dates', 'ckl-car-rental'),
            'edit_item' => __('Edit Blocked Dates', 'ckl-car-rental'),
            'new_item' => __('New Blocked Dates', 'ckl-car-rental'),
            'view_item' => __('View Blocked Dates', 'ckl-car-rental'),
            'search_items' => __('Search Blocked Dates', 'ckl-car-rental'),
            'not_found' => __('No blocked dates found', 'ckl-car-rental'),
            'not_found_in_trash' => __('No blocked dates found in trash', 'ckl-car-rental'),
        );

        $args = array(
            'labels' => $labels,
            'public' => false,
            'show_ui' => true,
            'show_in_menu' => 'edit.php?post_type=vehicle',
            'supports' => array('title'),
            'menu_icon' => 'dashicons-calendar-alt',
            'capabilities' => array(
                'edit_post' => 'edit_vehicle',
                'read_post' => 'read_vehicle',
                'delete_post' => 'delete_vehicle',
                'edit_posts' => 'edit_vehicles',
                'edit_others_posts' => 'edit_others_vehicles',
                'publish_posts' => 'publish_vehicles',
                'read_private_posts' => 'read_private_vehicles',
            ),
        );

        register_post_type('blocked_date', $args);
    }

    /**
     * Add meta boxes for blocked dates
     */
    public static function add_blocked_dates_meta_boxes() {
        // Blocked date details
        add_meta_box(
            'blocked_date_details',
            __('Blocked Date Details', 'ckl-car-rental'),
            array(__CLASS__, 'blocked_date_details_meta_box_html'),
            'blocked_date',
            'normal',
            'default'
        );

        // Calendar view for vehicles
        add_meta_box(
            'vehicle_blocked_dates_calendar',
            __('Blocked Dates Calendar', 'ckl-car-rental'),
            array(__CLASS__, 'vehicle_blocked_dates_calendar_html'),
            'vehicle'
        );
    }

    /**
     * Render blocked date details meta box
     */
    public static function blocked_date_details_meta_box_html($post) {
        wp_nonce_field('ckl_save_blocked_date', 'ckl_blocked_date_nonce');

        $vehicle_id = get_post_meta($post->ID, '_blocked_vehicle_id', true);
        $start_date = get_post_meta($post->ID, '_blocked_start_date', true);
        $end_date = get_post_meta($post->ID, '_blocked_end_date', true);
        $reason = get_post_meta($post->ID, '_blocked_reason', true);
        $notes = get_post_meta($post->ID, '_blocked_notes', true);

        // Get all vehicles
        $vehicles = get_posts(array(
            'post_type' => 'vehicle',
            'posts_per_page' => -1,
            'post_status' => 'publish',
        ));

        ?>
        <div class="ckl-blocked-date-details">
            <table class="form-table">
                <tr>
                    <th>
                        <label for="blocked_vehicle_id"><?php _e('Vehicle', 'ckl-car-rental'); ?></label>
                    </th>
                    <td>
                        <select name="blocked_vehicle_id" id="blocked_vehicle_id" required>
                            <option value=""><?php _e('Select Vehicle', 'ckl-car-rental'); ?></option>
                            <?php foreach ($vehicles as $vehicle): ?>
                                <option value="<?php echo $vehicle->ID; ?>" <?php selected($vehicle_id, $vehicle->ID); ?>>
                                    <?php echo esc_html($vehicle->post_title); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>

                <tr>
                    <th>
                        <label for="blocked_start_date"><?php _e('Start Date', 'ckl-car-rental'); ?></label>
                    </th>
                    <td>
                        <input type="date"
                               name="blocked_start_date"
                               id="blocked_start_date"
                               value="<?php echo esc_attr($start_date); ?>"
                               required>
                    </td>
                </tr>

                <tr>
                    <th>
                        <label for="blocked_end_date"><?php _e('End Date', 'ckl-car-rental'); ?></label>
                    </th>
                    <td>
                        <input type="date"
                               name="blocked_end_date"
                               id="blocked_end_date"
                               value="<?php echo esc_attr($end_date); ?>"
                               required>
                    </td>
                </tr>

                <tr>
                    <th>
                        <label for="blocked_reason"><?php _e('Reason', 'ckl-car-rental'); ?></label>
                    </th>
                    <td>
                        <select name="blocked_reason" id="blocked_reason">
                            <option value="maintenance" <?php selected($reason, 'maintenance'); ?>>
                                <?php _e('Maintenance', 'ckl-car-rental'); ?>
                            </option>
                            <option value="personal" <?php selected($reason, 'personal'); ?>>
                                <?php _e('Personal Use', 'ckl-car-rental'); ?>
                            </option>
                            <option value="reserved" <?php selected($reason, 'reserved'); ?>>
                                <?php _e('Reserved for VIP', 'ckl-car-rental'); ?>
                            </option>
                            <option value="holiday" <?php selected($reason, 'holiday'); ?>>
                                <?php _e('Holiday', 'ckl-car-rental'); ?>
                            </option>
                            <option value="other" <?php selected($reason, 'other'); ?>>
                                <?php _e('Other', 'ckl-car-rental'); ?>
                            </option>
                        </select>
                    </td>
                </tr>

                <tr>
                    <th>
                        <label for="blocked_notes"><?php _e('Notes', 'ckl-car-rental'); ?></label>
                    </th>
                    <td>
                        <textarea name="blocked_notes"
                                  id="blocked_notes"
                                  rows="4"
                                  class="large-text"><?php echo esc_textarea($notes); ?></textarea>
                    </td>
                </tr>
            </table>

            <?php if ($vehicle_id): ?>
                <p>
                    <strong><?php _e('Bookings Affected:', 'ckl-car-rental'); ?></strong>
                    <?php
                    $conflicting_bookings = self::get_conflicting_bookings($vehicle_id, $start_date, $end_date);
                    if ($conflicting_bookings) {
                        echo '<span style="color: #a00;">' . count($conflicting_bookings) . ' ' . __('conflicting bookings', 'ckl-car-rental') . '</span>';
                    } else {
                        echo '<span style="color: #090;">' . __('No conflicts', 'ckl-car-rental') . '</span>';
                    }
                    ?>
                </p>
            <?php endif; ?>
        </div>
        <?php
    }

    /**
     * Render vehicle blocked dates calendar
     */
    public static function vehicle_blocked_dates_calendar_html($post) {
        $vehicle_id = $post->ID;
        $blocked_dates = self::get_blocked_dates_for_vehicle($vehicle_id);

        ?>
        <div class="ckl-blocked-dates-calendar-wrapper">
            <div id="blocked-dates-calendar"></div>

            <p class="description">
                <?php _e('Click and drag on the calendar to block dates. Click on existing blocked dates to edit or delete.', 'ckl-car-rental'); ?>
            </p>
        </div>

        <style>
            #blocked-dates-calendar {
                min-height: 400px;
                background: #f9f9f9;
                border: 1px solid #ddd;
                padding: 15px;
            }
        </style>

        <script>
        jQuery(document).ready(function($) {
            var blockedDates = <?php echo json_encode($blocked_dates); ?>;

            // Initialize FullCalendar if available, otherwise show a message
            if ($.fn.fullCalendar) {
                $('#blocked-dates-calendar').fullCalendar({
                    header: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'month,agendaWeek,agendaDay'
                    },
                    events: blockedDates.map(function(date) {
                        return {
                            title: date.reason,
                            start: date.start,
                            end: date.end,
                            backgroundColor: date.color || '#ff0000',
                            id: date.id
                        };
                    }),
                    selectable: true,
                    selectHelper: true,
                    select: function(start, end) {
                        // Open modal to create new blocked date
                        var startDate = start.format('YYYY-MM-DD');
                        var endDate = end.format('YYYY-MM-DD');

                        // Redirect to add new blocked date with pre-filled dates
                        window.location.href = '<?php echo admin_url('post-new.php?post_type=blocked_date'); ?>'
                            + '&vehicle_id=<?php echo $vehicle_id; ?>'
                            + '&start_date=' + startDate
                            + '&end_date=' + endDate;
                    },
                    eventClick: function(calEvent) {
                        // Redirect to edit blocked date
                        window.location.href = '<?php echo admin_url('post.php?post='); ?>' + calEvent.id + '&action=edit';
                    }
                });
            } else {
                $('#blocked-dates-calendar').html('<p><?php _e('Calendar view requires FullCalendar library. Blocked dates are listed below:', 'ckl-car-rental'); ?></p>');

                var listHtml = '<ul>';
                blockedDates.forEach(function(date) {
                    listHtml += '<li>' + date.start + ' to ' + date.end + ' - ' + date.reason + '</li>';
                });
                listHtml += '</ul>';
                $('#blocked-dates-calendar').append(listHtml);
            }
        });
        </script>
        <?php
    }

    /**
     * Save blocked date meta
     */
    public static function save_blocked_date_meta($post_id) {
        // Verify nonce
        if (!isset($_POST['ckl_blocked_date_nonce']) || !wp_verify_nonce($_POST['ckl_blocked_date_nonce'], 'ckl_save_blocked_date')) {
            return;
        }

        // Check autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // Check permissions
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        // Save vehicle ID
        $vehicle_id = isset($_POST['blocked_vehicle_id']) ? intval($_POST['blocked_vehicle_id']) : 0;
        if ($vehicle_id > 0) {
            update_post_meta($post_id, '_blocked_vehicle_id', $vehicle_id);
        }

        // Save dates
        if (isset($_POST['blocked_start_date'])) {
            update_post_meta($post_id, '_blocked_start_date', sanitize_text_field($_POST['blocked_start_date']));
        }

        if (isset($_POST['blocked_end_date'])) {
            update_post_meta($post_id, '_blocked_end_date', sanitize_text_field($_POST['blocked_end_date']));
        }

        // Save reason
        if (isset($_POST['blocked_reason'])) {
            update_post_meta($post_id, '_blocked_reason', sanitize_text_field($_POST['blocked_reason']));
        }

        // Save notes
        if (isset($_POST['blocked_notes'])) {
            update_post_meta($post_id, '_blocked_notes', sanitize_textarea_field($_POST['blocked_notes']));
        }

        // Save who blocked it
        update_post_meta($post_id, '_blocked_by', get_current_user_id());
        update_post_meta($post_id, '_blocked_at', current_time('mysql'));

        // Apply to WooCommerce
        if ($vehicle_id > 0) {
            self::apply_blocked_dates_to_woocommerce($post_id);
        }
    }

    /**
     * Apply blocked dates to WooCommerce availability
     */
    private static function apply_blocked_dates_to_woocommerce($blocked_date_id) {
        $vehicle_id = get_post_meta($blocked_date_id, '_blocked_vehicle_id', true);
        $start_date = get_post_meta($blocked_date_id, '_blocked_start_date', true);
        $end_date = get_post_meta($blocked_date_id, '_blocked_end_date', true);

        if (!$vehicle_id || !$start_date || !$end_date) {
            return;
        }

        // Get WooCommerce product ID
        $product_id = get_post_meta($vehicle_id, '_vehicle_woocommerce_product_id', true);

        if (!$product_id) {
            return;
        }

        $booking_product = wc_get_product($product_id);

        if (!$booking_product) {
            return;
        }

        // Add availability exception
        // Note: WooCommerce Bookings stores availability in post meta
        $availability_rules = get_post_meta($product_id, '_wc_booking_availability', true);

        if (!is_array($availability_rules)) {
            $availability_rules = array();
        }

        $availability_rules[] = array(
            'type' => 'custom',
            'from' => $start_date,
            'to' => $end_date,
            'bookable' => 'no',
            'priority' => 10,
        );

        update_post_meta($product_id, '_wc_booking_availability', $availability_rules);

        // Clear product cache
        wp_cache_delete($product_id, 'post_meta');
    }

    /**
     * Validate booking against blocked dates
     */
    public static function validate_booking_against_blocked_dates($is_valid, $product_id, $start_date, $end_date) {
        // Get vehicle ID from product
        $vehicle_id = get_post_meta($product_id, '_vehicle_id', true);

        if (!$vehicle_id) {
            return $is_valid;
        }

        // Get all blocked dates for this vehicle
        $blocked_dates = self::get_blocked_dates_for_vehicle($vehicle_id);

        // Check if requested dates overlap with any blocked dates
        foreach ($blocked_dates as $blocked) {
            $blocked_start = $blocked['start'];
            $blocked_end = $blocked['end'];

            if (self::date_ranges_overlap($start_date, $end_date, $blocked_start, $blocked_end)) {
                return new WP_Error(
                    'blocked_date',
                    sprintf(
                        __('This vehicle is not available from %s to %s. Reason: %s', 'ckl-car-rental'),
                        $blocked_start,
                        $blocked_end,
                        $blocked['reason']
                    )
                );
            }
        }

        return $is_valid;
    }

    /**
     * Check if two date ranges overlap
     */
    private static function date_ranges_overlap($start1, $end1, $start2, $end2) {
        $start_ts = strtotime($start1);
        $end_ts = strtotime($end1);
        $blocked_start_ts = strtotime($start2);
        $blocked_end_ts = strtotime($end2);

        return ($start_ts <= $blocked_end_ts) && ($end_ts >= $blocked_start_ts);
    }

    /**
     * Get blocked dates for vehicle
     */
    public static function get_blocked_dates_for_vehicle($vehicle_id) {
        $blocked_dates = get_posts(array(
            'post_type' => 'blocked_date',
            'posts_per_page' => -1,
            'meta_query' => array(
                array(
                    'key' => '_blocked_vehicle_id',
                    'value' => $vehicle_id,
                ),
            ),
        ));

        $result = array();
        $colors = array(
            'maintenance' => '#d63638',
            'personal' => '#dba617',
            'reserved' => '#00a32a',
            'holiday' => '#3858e9',
            'other' => '#646970',
        );

        foreach ($blocked_dates as $blocked) {
            $result[] = array(
                'id' => $blocked->ID,
                'start' => get_post_meta($blocked->ID, '_blocked_start_date', true),
                'end' => get_post_meta($blocked->ID, '_blocked_end_date', true),
                'reason' => get_post_meta($blocked->ID, '_blocked_reason', true),
                'notes' => get_post_meta($blocked->ID, '_blocked_notes', true),
                'color' => isset($colors[get_post_meta($blocked->ID, '_blocked_reason', true)]) ? $colors[get_post_meta($blocked->ID, '_blocked_reason', true)] : $colors['other'],
            );
        }

        return $result;
    }

    /**
     * Get conflicting bookings
     */
    private static function get_conflicting_bookings($vehicle_id, $start_date, $end_date) {
        $bookings = get_posts(array(
            'post_type' => 'wc_booking',
            'posts_per_page' => -1,
            'post_status' => array('wc-booked', 'wc-pending', 'wc-confirmed', 'wc-paid'),
            'meta_query' => array(
                array(
                    'key' => '_booking_vehicle_id',
                    'value' => $vehicle_id,
                ),
            ),
        ));

        $conflicting = array();

        foreach ($bookings as $booking_post) {
            $booking = get_wc_booking($booking_post->ID);
            if ($booking) {
                $booking_start = $booking->get_start_date('Y-m-d');
                $booking_end = $booking->get_end_date('Y-m-d');

                if (self::date_ranges_overlap($start_date, $end_date, $booking_start, $booking_end)) {
                    $conflicting[] = $booking_post->ID;
                }
            }
        }

        return $conflicting;
    }

    /**
     * Handle bulk block dates
     */
    public static function handle_bulk_block_dates() {
        // Verify nonce
        if (!isset($_POST['ckl_bulk_block_nonce']) || !wp_verify_nonce($_POST['ckl_bulk_block_nonce'], 'ckl_bulk_block_dates')) {
            wp_die(__('Security check failed', 'ckl-car-rental'));
        }

        // Check permissions
        if (!current_user_can('edit_vehicles')) {
            wp_die(__('Permission denied', 'ckl-car-rental'));
        }

        if (!isset($_POST['bulk_block_dates'])) {
            return;
        }

        $vehicles = isset($_POST['vehicles']) ? $_POST['vehicles'] : array();
        $start_date = isset($_POST['start_date']) ? sanitize_text_field($_POST['start_date']) : '';
        $end_date = isset($_POST['end_date']) ? sanitize_text_field($_POST['end_date']) : '';
        $reason = isset($_POST['reason']) ? sanitize_text_field($_POST['reason']) : 'maintenance';
        $notes = isset($_POST['notes']) ? sanitize_textarea_field($_POST['notes']) : '';

        if (empty($vehicles) || empty($start_date) || empty($end_date)) {
            wp_redirect(admin_url('edit.php?post_type=blocked_date&error=missing_data'));
            exit;
        }

        $created = 0;

        foreach ($vehicles as $vehicle_id) {
            // Create blocked date post for each vehicle
            $vehicle = get_post($vehicle_id);
            if (!$vehicle) {
                continue;
            }

            $blocked_date = array(
                'post_title' => $vehicle->post_title . ' - ' . $start_date . ' to ' . $end_date,
                'post_type' => 'blocked_date',
                'post_status' => 'publish',
            );

            $post_id = wp_insert_post($blocked_date);

            if ($post_id && !is_wp_error($post_id)) {
                // Save meta data
                update_post_meta($post_id, '_blocked_vehicle_id', $vehicle_id);
                update_post_meta($post_id, '_blocked_start_date', $start_date);
                update_post_meta($post_id, '_blocked_end_date', $end_date);
                update_post_meta($post_id, '_blocked_reason', $reason);
                update_post_meta($post_id, '_blocked_notes', $notes);
                update_post_meta($post_id, '_blocked_by', get_current_user_id());
                update_post_meta($post_id, '_blocked_at', current_time('mysql'));

                // Apply to WooCommerce
                self::apply_blocked_dates_to_woocommerce($post_id);

                $created++;
            }
        }

        wp_redirect(admin_url('edit.php?post_type=blocked_date&created=' . $created));
        exit;
    }

    /**
     * AJAX handler for getting blocked dates
     */
    public static function ajax_get_blocked_dates() {
        check_ajax_referer('ckl-blocked-dates', 'nonce');

        $vehicle_id = intval($_POST['vehicle_id']);

        if (!$vehicle_id) {
            wp_send_json_error(array('message' => __('Invalid vehicle ID', 'ckl-car-rental')));
        }

        $blocked_dates = self::get_blocked_dates_for_vehicle($vehicle_id);

        wp_send_json_success(array(
            'blocked_dates' => $blocked_dates,
        ));
    }
}
