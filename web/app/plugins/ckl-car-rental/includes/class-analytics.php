<?php
/**
 * CKL Analytics
 *
 * Handles analytics and reporting for the car rental system
 */

if (!defined('ABSPATH')) {
    exit;
}

class CKL_Analytics {

    /**
     * Initialize the class
     */
    public static function init() {
        add_action('wp_dashboard_setup', array(__CLASS__, 'add_dashboard_widgets'));
        add_action('admin_menu', array(__CLASS__, 'add_analytics_page'));
        add_action('wp_ajax_ckl_get_analytics', array(__CLASS__, 'ajax_get_analytics'));
    }

    /**
     * Add dashboard widgets
     */
    public static function add_dashboard_widgets() {
        if (current_user_can('manage_options')) {
            wp_add_dashboard_widget(
                'ckl_analytics_overview',
                __('CKL Car Rental Analytics', 'ckl-car-rental'),
                array(__CLASS__, 'dashboard_widget_html')
            );
        }
    }

    /**
     * Render dashboard widget
     */
    public static function dashboard_widget_html() {
        $data = self::get_analytics_data('30 days');
        ?>
        <div class="ckl-analytics-widget">
            <div class="analytics-metrics">
                <div class="metric">
                    <span class="metric-label"><?php _e('Total Bookings', 'ckl-car-rental'); ?></span>
                    <span class="metric-value"><?php echo $data['total_bookings']; ?></span>
                </div>

                <div class="metric">
                    <span class="metric-label"><?php _e('Total Revenue', 'ckl-car-rental'); ?></span>
                    <span class="metric-value"><?php echo wc_price($data['total_revenue']); ?></span>
                </div>

                <div class="metric">
                    <span class="metric-label"><?php _e('Avg. Booking Value', 'ckl-car-rental'); ?></span>
                    <span class="metric-value"><?php echo wc_price($data['average_booking_value']); ?></span>
                </div>
            </div>

            <p>
                <a href="<?php echo admin_url('admin.php?page=ckl-analytics'); ?>">
                    <?php _e('View Full Analytics →', 'ckl-car-rental'); ?>
                </a>
            </p>
        </div>
        <style>
            .ckl-analytics-widget .analytics-metrics {
                display: flex;
                justify-content: space-between;
                gap: 15px;
                margin-bottom: 15px;
            }
            .ckl-analytics-widget .metric {
                flex: 1;
                text-align: center;
            }
            .ckl-analytics-widget .metric-label {
                display: block;
                font-size: 12px;
                color: #666;
            }
            .ckl-analytics-widget .metric-value {
                display: block;
                font-size: 18px;
                font-weight: bold;
                color: #2271b1;
            }
        </style>
        <?php
    }

    /**
     * Add analytics page
     */
    public static function add_analytics_page() {
        add_submenu_page(
            'woocommerce',
            __('CKL Analytics', 'ckl-car-rental'),
            __('CKL Analytics', 'ckl-car-rental'),
            'manage_options',
            'ckl-analytics',
            array(__CLASS__, 'render_analytics_page')
        );
    }

    /**
     * Render analytics page
     */
    public static function render_analytics_page() {
        $date_range = isset($_GET['date_range']) ? sanitize_text_field($_GET['date_range']) : '30 days';
        $data = self::get_analytics_data($date_range);

        ?>
        <div class="wrap ckl-analytics-page">
            <h1><?php _e('CKL Car Rental Analytics', 'ckl-car-rental'); ?></h1>

            <div class="date-range-selector">
                <form method="get">
                    <input type="hidden" name="page" value="ckl-analytics">
                    <select name="date_range">
                        <option value="7 days" <?php selected($date_range, '7 days'); ?>><?php _e('Last 7 Days', 'ckl-car-rental'); ?></option>
                        <option value="30 days" <?php selected($date_range, '30 days'); ?>><?php _e('Last 30 Days', 'ckl-car-rental'); ?></option>
                        <option value="90 days" <?php selected($date_range, '90 days'); ?>><?php _e('Last 90 Days', 'ckl-car-rental'); ?></option>
                        <option value="1 year" <?php selected($date_range, '1 year'); ?>><?php _e('Last Year', 'ckl-car-rental'); ?></option>
                        <option value="all" <?php selected($date_range, 'all'); ?>><?php _e('All Time', 'ckl-car-rental'); ?></option>
                    </select>
                    <button type="submit" class="button"><?php _e('Apply', 'ckl-car-rental'); ?></button>
                </form>
            </div>

            <div class="analytics-overview">
                <div class="analytics-card">
                    <h3><?php _e('Bookings Overview', 'ckl-car-rental'); ?></h3>
                    <table class="wp-list-table widefat fixed striped">
                        <tr>
                            <td><?php _e('Total Bookings', 'ckl-car-rental'); ?></td>
                            <td><?php echo $data['total_bookings']; ?></td>
                        </tr>
                        <tr>
                            <td><?php _e('Pending', 'ckl-car-rental'); ?></td>
                            <td><?php echo $data['status_breakdown']['pending']; ?></td>
                        </tr>
                        <tr>
                            <td><?php _e('Confirmed', 'ckl-car-rental'); ?></td>
                            <td><?php echo $data['status_breakdown']['confirmed']; ?></td>
                        </tr>
                        <tr>
                            <td><?php _e('Completed', 'ckl-car-rental'); ?></td>
                            <td><?php echo $data['status_breakdown']['complete']; ?></td>
                        </tr>
                        <tr>
                            <td><?php _e('Cancelled', 'ckl-car-rental'); ?></td>
                            <td><?php echo $data['status_breakdown']['cancelled']; ?></td>
                        </tr>
                    </table>
                </div>

                <div class="analytics-card">
                    <h3><?php _e('Revenue', 'ckl-car-rental'); ?></h3>
                    <table class="wp-list-table widefat fixed striped">
                        <tr>
                            <td><?php _e('Total Revenue', 'ckl-car-rental'); ?></td>
                            <td><?php echo wc_price($data['total_revenue']); ?></td>
                        </tr>
                        <tr>
                            <td><?php _e('Average Booking Value', 'ckl-car-rental'); ?></td>
                            <td><?php echo wc_price($data['average_booking_value']); ?></td>
                        </tr>
                        <tr>
                            <td><?php _e('Late Fees Collected', 'ckl-car-rental'); ?></td>
                            <td><?php echo wc_price($data['late_fees_collected']); ?></td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="analytics-overview">
                <div class="analytics-card">
                    <h3><?php _e('Vehicle Utilization', 'ckl-car-rental'); ?></h3>
                    <table class="wp-list-table widefat fixed striped">
                        <thead>
                            <tr>
                                <th><?php _e('Vehicle', 'ckl-car-rental'); ?></th>
                                <th><?php _e('Bookings', 'ckl-car-rental'); ?></th>
                                <th><?php _e('Revenue', 'ckl-car-rental'); ?></th>
                                <th><?php _e('Utilization', 'ckl-car-rental'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data['vehicle_utilization'] as $vehicle): ?>
                                <tr>
                                    <td><?php echo esc_html($vehicle['name']); ?></td>
                                    <td><?php echo $vehicle['bookings']; ?></td>
                                    <td><?php echo wc_price($vehicle['revenue']); ?></td>
                                    <td><?php echo $vehicle['utilization']; ?>%</td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="analytics-card">
                    <h3><?php _e('Popular Vehicles', 'ckl-car-rental'); ?></h3>
                    <table class="wp-list-table widefat fixed striped">
                        <thead>
                            <tr>
                                <th><?php _e('Rank', 'ckl-car-rental'); ?></th>
                                <th><?php _e('Vehicle', 'ckl-car-rental'); ?></th>
                                <th><?php _e('Bookings', 'ckl-car-rental'); ?></th>
                                <th><?php _e('Rating', 'ckl-car-rental'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data['popular_vehicles'] as $index => $vehicle): ?>
                                <tr>
                                    <td><?php echo $index + 1; ?></td>
                                    <td>
                                        <a href="<?php echo get_edit_post_link($vehicle['id']); ?>">
                                            <?php echo esc_html($vehicle['name']); ?>
                                        </a>
                                    </td>
                                    <td><?php echo $vehicle['bookings']; ?></td>
                                    <td><?php echo $vehicle['rating']; ?> ★</td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="analytics-card">
                <h3><?php _e('Late Returns', 'ckl-car-rental'); ?></h3>
                <table class="wp-list-table widefat fixed striped">
                    <tr>
                        <td><?php _e('Total Late Returns', 'ckl-car-rental'); ?></td>
                        <td><?php echo $data['late_returns']['count']; ?></td>
                    </tr>
                    <tr>
                        <td><?php _e('Late Return Rate', 'ckl-car-rental'); ?></td>
                        <td><?php echo $data['late_returns']['rate']; ?>%</td>
                    </tr>
                    <tr>
                        <td><?php _e('Average Hours Late', 'ckl-car-rental'); ?></td>
                        <td><?php echo $data['late_returns']['avg_hours']; ?></td>
                    </tr>
                </table>
            </div>
        </div>
        <style>
            .ckl-analytics-page .date-range-selector {
                margin: 20px 0;
                padding: 15px;
                background: #fff;
                border: 1px solid #ccc;
            }
            .ckl-analytics-page .analytics-overview {
                display: flex;
                gap: 20px;
                margin-bottom: 20px;
            }
            .ckl-analytics-page .analytics-overview .analytics-card {
                flex: 1;
            }
            .ckl-analytics-page .analytics-card {
                background: #fff;
                padding: 20px;
                margin-bottom: 20px;
                border: 1px solid #ccd0d4;
                box-shadow: 0 1px 1px rgba(0,0,0,.04);
            }
            .ckl-analytics-page .analytics-card h3 {
                margin-top: 0;
            }
        </style>
        <?php
    }

    /**
     * Get analytics data
     */
    public static function get_analytics_data($date_range = '30 days') {
        // Calculate date range
        $start_date = '';
        $end_date = current_time('Y-m-d H:i:s');

        switch ($date_range) {
            case '7 days':
                $start_date = date('Y-m-d H:i:s', strtotime('-7 days'));
                break;
            case '30 days':
                $start_date = date('Y-m-d H:i:s', strtotime('-30 days'));
                break;
            case '90 days':
                $start_date = date('Y-m-d H:i:s', strtotime('-90 days'));
                break;
            case '1 year':
                $start_date = date('Y-m-d H:i:s', strtotime('-1 year'));
                break;
            case 'all':
                $start_date = '2000-01-01 00:00:00';
                break;
            default:
                $start_date = date('Y-m-d H:i:s', strtotime('-30 days'));
        }

        // Get bookings
        $bookings = get_posts(array(
            'post_type' => 'wc_booking',
            'posts_per_page' => -1,
            'date_query' => array(
                array(
                    'after' => $start_date,
                    'before' => $end_date,
                    'inclusive' => true,
                ),
            ),
        ));

        // Initialize data
        $data = array(
            'total_bookings' => count($bookings),
            'total_revenue' => 0,
            'average_booking_value' => 0,
            'status_breakdown' => array(
                'pending' => 0,
                'confirmed' => 0,
                'complete' => 0,
                'cancelled' => 0,
            ),
            'vehicle_utilization' => array(),
            'popular_vehicles' => array(),
            'late_returns' => array(
                'count' => 0,
                'rate' => 0,
                'avg_hours' => 0,
            ),
            'late_fees_collected' => 0,
        );

        $vehicle_revenue = array();
        $vehicle_bookings = array();

        foreach ($bookings as $booking_post) {
            $booking = get_wc_booking($booking_post->ID);
            if (!$booking) {
                continue;
            }

            $status = $booking->get_status();
            $order_id = $booking->get_order_id();
            $order = wc_get_order($order_id);

            // Status breakdown
            if (isset($data['status_breakdown'][$status])) {
                $data['status_breakdown'][$status]++;
            }

            // Revenue
            if ($order && in_array($status, array('complete', 'confirmed', 'paid'))) {
                $data['total_revenue'] += $order->get_total();
            }

            // Vehicle data
            $product_id = $booking->get_product_id();
            $vehicle_id = get_post_meta($product_id, '_vehicle_id', true);

            if ($vehicle_id) {
                if (!isset($vehicle_revenue[$vehicle_id])) {
                    $vehicle_revenue[$vehicle_id] = 0;
                    $vehicle_bookings[$vehicle_id] = 0;
                }

                if ($order && in_array($status, array('complete', 'confirmed', 'paid'))) {
                    $vehicle_revenue[$vehicle_id] += $order->get_total();
                }

                $vehicle_bookings[$vehicle_id]++;
            }

            // Late returns
            $late_hours = get_post_meta($booking_post->ID, '_late_fee_hours', true);
            if ($late_hours) {
                $data['late_returns']['count']++;
                $data['late_returns']['avg_hours'] += floatval($late_hours);

                $late_fee = get_post_meta($booking_post->ID, '_late_fee_amount', true);
                if ($late_fee) {
                    $data['late_fees_collected'] += floatval($late_fee);
                }
            }
        }

        // Calculate averages
        if ($data['total_bookings'] > 0) {
            $data['average_booking_value'] = $data['total_revenue'] / $data['total_bookings'];
            $data['late_returns']['rate'] = round(($data['late_returns']['count'] / $data['total_bookings']) * 100, 2);
            $data['late_returns']['avg_hours'] = round($data['late_returns']['avg_hours'] / max(1, $data['late_returns']['count']), 2);
        }

        // Vehicle utilization
        foreach ($vehicle_revenue as $vehicle_id => $revenue) {
            $vehicle = get_post($vehicle_id);
            if (!$vehicle) {
                continue;
            }

            $data['vehicle_utilization'][] = array(
                'id' => $vehicle_id,
                'name' => $vehicle->post_title,
                'revenue' => $revenue,
                'bookings' => $vehicle_bookings[$vehicle_id],
                'utilization' => self::calculate_utilization($vehicle_id, $start_date, $end_date),
            );
        }

        // Sort by revenue
        usort($data['vehicle_utilization'], function($a, $b) {
            return $b['revenue'] - $a['revenue'];
        });

        // Popular vehicles
        foreach ($vehicle_bookings as $vehicle_id => $bookings) {
            $vehicle = get_post($vehicle_id);
            if (!$vehicle) {
                continue;
            }

            $rating = CKL_Reviews::get_vehicle_average_rating($vehicle_id);

            $data['popular_vehicles'][] = array(
                'id' => $vehicle_id,
                'name' => $vehicle->post_title,
                'bookings' => $bookings,
                'rating' => $rating,
            );
        }

        // Sort by bookings
        usort($data['popular_vehicles'], function($a, $b) {
            return $b['bookings'] - $a['bookings'];
        });

        return $data;
    }

    /**
     * Calculate vehicle utilization
     */
    private static function calculate_utilization($vehicle_id, $start_date, $end_date) {
        // Get all bookings for this vehicle
        $bookings = get_posts(array(
            'post_type' => 'wc_booking',
            'posts_per_page' => -1,
            'date_query' => array(
                array(
                    'after' => $start_date,
                    'before' => $end_date,
                    'inclusive' => true,
                ),
            ),
            'meta_query' => array(
                array(
                    'key' => '_booking_vehicle_id',
                    'value' => $vehicle_id,
                ),
            ),
        ));

        $total_hours = 0;
        $period_hours = (strtotime($end_date) - strtotime($start_date)) / 3600;

        foreach ($bookings as $booking_post) {
            $booking = get_wc_booking($booking_post->ID);
            if ($booking) {
                $start = strtotime($booking->get_start_date());
                $end = strtotime($booking->get_end_date());
                $total_hours += ($end - $start) / 3600;
            }
        }

        if ($period_hours > 0) {
            return round(($total_hours / $period_hours) * 100, 2);
        }

        return 0;
    }

    /**
     * AJAX handler for analytics data
     */
    public static function ajax_get_analytics() {
        check_ajax_referer('ckl-analytics', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('Permission denied', 'ckl-car-rental')));
        }

        $date_range = isset($_POST['date_range']) ? sanitize_text_field($_POST['date_range']) : '30 days';
        $data = self::get_analytics_data($date_range);

        wp_send_json_success($data);
    }
}
