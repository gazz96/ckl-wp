<?php
/**
 * CKL Reviews
 *
 * Handles review and rating system using WordPress comments
 */

if (!defined('ABSPATH')) {
    exit;
}

class CKL_Reviews {

    /**
     * Initialize the class
     */
    public static function init() {
        add_action('comment_post', array(__CLASS__, 'save_review_meta'), 10, 3);
        add_action('add_meta_boxes', array(__CLASS__, 'add_review_meta_box'));
        add_filter('comments_template', array(__CLASS__, 'load_reviews_template'));
        add_filter('comment_form_defaults', array(__CLASS__, 'customize_comment_form'));
        add_shortcode('ckl_vehicle_reviews', array(__CLASS__, 'vehicle_reviews_shortcode'));
    }

    /**
     * Save review meta data
     */
    public static function save_review_meta($comment_id, $comment_approved, $comment_data) {
        // Check if this is a vehicle review
        if (!isset($_POST['ckl_rating']) || !isset($_POST['ckl_booking_id'])) {
            return;
        }

        // Save rating
        $rating = intval($_POST['ckl_rating']);
        if ($rating >= 1 && $rating <= 5) {
            update_comment_meta($comment_id, 'ckl_rating', $rating);
        }

        // Save booking ID
        $booking_id = intval($_POST['ckl_booking_id']);
        if ($booking_id > 0) {
            update_comment_meta($comment_id, 'ckl_booking_id', $booking_id);

            // Verify this is a valid booking for this customer
            $booking = get_wc_booking($booking_id);
            if ($booking) {
                $order_id = $booking->get_order_id();
                $order = wc_get_order($order_id);

                if ($order) {
                    $order_user_id = $order->get_user_id();
                    $comment_user_id = $comment_data['user_id'];

                    // Mark as verified if user matches
                    if ($order_user_id === $comment_user_id) {
                        update_comment_meta($comment_id, 'ckl_verified_purchase', 'yes');
                    }
                }
            }
        }
    }

    /**
     * Add rating meta box to comments
     */
    public static function add_review_meta_box() {
        add_meta_box(
            'ckl_review_details',
            __('CKL Review Details', 'ckl-car-rental'),
            array(__CLASS__, 'review_meta_box_html'),
            'comment',
            'normal',
            'default'
        );
    }

    /**
     * Render review meta box
     */
    public static function review_meta_box_html($comment) {
        $rating = get_comment_meta($comment->comment_ID, 'ckl_rating', true);
        $booking_id = get_comment_meta($comment->comment_ID, 'ckl_booking_id', true);
        $verified = get_comment_meta($comment->comment_ID, 'ckl_verified_purchase', true);

        ?>
        <div class="ckl-review-details">
            <p>
                <strong><?php _e('Rating:', 'ckl-car-rental'); ?></strong>
                <?php if ($rating): ?>
                    <?php echo self::get_star_rating_html($rating); ?>
                <?php else: ?>
                    <?php _e('No rating', 'ckl-car-rental'); ?>
                <?php endif; ?>
            </p>

            <?php if ($booking_id): ?>
                <p>
                    <strong><?php _e('Booking ID:', 'ckl-car-rental'); ?></strong>
                    <a href="<?php echo get_edit_post_link($booking_id); ?>">#<?php echo $booking_id; ?></a>
                </p>
            <?php endif; ?>

            <?php if ($verified === 'yes'): ?>
                <p>
                    <span class="badge badge-success">
                        <?php _e('✓ Verified Purchase', 'ckl-car-rental'); ?>
                    </span>
                </p>
            <?php endif; ?>
        </div>
        <?php
    }

    /**
     * Get star rating HTML
     */
    public static function get_star_rating_html($rating) {
        $html = '<div class="star-rating">';
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $rating) {
                $html .= '★';
            } else {
                $html .= '☆';
            }
        }
        $html .= '</div>';
        return $html;
    }

    /**
     * Get average rating for vehicle
     */
    public static function get_vehicle_average_rating($vehicle_id) {
        $args = array(
            'post_id' => $vehicle_id,
            'status' => 'approve',
            'meta_query' => array(
                array(
                    'key' => 'ckl_rating',
                    'compare' => 'EXISTS',
                ),
            ),
        );

        $comments = get_comments($args);

        if (empty($comments)) {
            return 0;
        }

        $total = 0;
        $count = 0;

        foreach ($comments as $comment) {
            $rating = get_comment_meta($comment->comment_ID, 'ckl_rating', true);
            if ($rating) {
                $total += $rating;
                $count++;
            }
        }

        if ($count === 0) {
            return 0;
        }

        return round($total / $count, 1);
    }

    /**
     * Get vehicle reviews
     */
    public static function get_vehicle_reviews($vehicle_id, $limit = 10) {
        $args = array(
            'post_id' => $vehicle_id,
            'status' => 'approve',
            'number' => $limit,
            'meta_query' => array(
                array(
                    'key' => 'ckl_rating',
                    'compare' => 'EXISTS',
                ),
            ),
        );

        return get_comments($args);
    }

    /**
     * Load custom reviews template
     */
    public static function load_reviews_template($template) {
        if (get_post_type() === 'vehicle') {
            return CKL_CAR_RENTAL_PLUGIN_DIR . 'templates/reviews-template.php';
        }
        return $template;
    }

    /**
     * Customize comment form for vehicles
     */
    public static function customize_comment_form($defaults) {
        if (get_post_type() === 'vehicle') {
            // Add rating field
            $rating_field = '<p class="comment-form-rating">';
            $rating_field .= '<label for="ckl_rating">' . __('Rating', 'ckl-car-rental') . '</label>';
            $rating_field .= '<select name="ckl_rating" id="ckl_rating" required>';
            $rating_field .= '<option value="">' . __('Select Rating', 'ckl-car-rental') . '</option>';
            for ($i = 5; $i >= 1; $i--) {
                $rating_field .= sprintf('<option value="%d">%d %s</option>', $i, $i, _n('Star', 'Stars', $i, 'ckl-car-rental'));
            }
            $rating_field .= '</select></p>';

            // Add booking ID field (will be populated from URL parameter after booking)
            $booking_field = '<input type="hidden" name="ckl_booking_id" id="ckl_booking_id" value="">';

            $defaults['comment_field'] = $rating_field . $booking_field . $defaults['comment_field'];
            $defaults['title_reply'] = __('Leave a Review', 'ckl-car-rental');
            $defaults['label_submit'] = __('Submit Review', 'ckl-car-rental');
        }

        return $defaults;
    }

    /**
     * Vehicle reviews shortcode
     */
    public static function vehicle_reviews_shortcode($atts) {
        $atts = shortcode_atts(array(
            'vehicle_id' => get_the_ID(),
            'limit' => 10,
            'show_average' => 'yes',
        ), $atts);

        $vehicle_id = intval($atts['vehicle_id']);
        $limit = intval($atts['limit']);

        ob_start();

        if ($atts['show_average'] === 'yes') {
            $average = self::get_vehicle_average_rating($vehicle_id);
            $count = wp_count_comments($vehicle_id)->approved;

            echo '<div class="ckl-reviews-summary">';
            echo '<div class="average-rating">';
            echo self::get_star_rating_html($average);
            echo '<span class="rating-value">' . $average . '</span>';
            echo '<span class="rating-count">(' . $count . ' ' . __('reviews', 'ckl-car-rental') . ')</span>';
            echo '</div>';
            echo '</div>';
        }

        $reviews = self::get_vehicle_reviews($vehicle_id, $limit);

        if ($reviews) {
            echo '<div class="ckl-reviews-list">';
            foreach ($reviews as $review) {
                $rating = get_comment_meta($review->comment_ID, 'ckl_rating', true);
                $verified = get_comment_meta($review->comment_ID, 'ckl_verified_purchase', true);

                echo '<div class="ckl-review-item">';
                echo '<div class="review-rating">' . self::get_star_rating_html($rating) . '</div>';

                if ($verified === 'yes') {
                    echo '<span class="verified-badge">✓ ' . __('Verified Purchase', 'ckl-car-rental') . '</span>';
                }

                echo '<div class="review-author">' . get_comment_author_link($review->comment_ID) . '</div>';
                echo '<div class="review-date">' . get_comment_date('', $review->comment_ID) . '</div>';
                echo '<div class="review-content">' . wp_kses_post(get_comment_text($review->comment_ID)) . '</div>';
                echo '</div>';
            }
            echo '</div>';
        } else {
            echo '<p>' . __('No reviews yet.', 'ckl-car-rental') . '</p>';
        }

        return ob_get_clean();
    }

    /**
     * Check if user can review vehicle
     */
    public static function can_user_review_vehicle($vehicle_id, $user_id = 0) {
        if (!$user_id) {
            $user_id = get_current_user_id();
        }

        if (!$user_id) {
            return false;
        }

        // Check if user has completed booking for this vehicle
        $args = array(
            'customer_id' => $user_id,
            'status' => array('wc-completed'),
        );

        $orders = wc_get_orders($args);

        foreach ($orders as $order) {
            foreach ($order->get_items() as $item) {
                $product_id = $item->get_product_id();
                $booking_vehicle_id = get_post_meta($product_id, '_vehicle_id', true);

                if ($booking_vehicle_id == $vehicle_id) {
                    // Check if already reviewed
                    $existing_reviews = get_comments(array(
                        'user_id' => $user_id,
                        'post_id' => $vehicle_id,
                        'meta_query' => array(
                            array(
                                'key' => 'ckl_booking_id',
                                'compare' => 'EXISTS',
                            ),
                        ),
                    ));

                    if (empty($existing_reviews)) {
                        return true;
                    }
                }
            }
        }

        return false;
    }
}
