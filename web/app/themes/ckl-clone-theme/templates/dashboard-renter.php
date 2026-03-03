<?php
/**
 * Renter Dashboard Template
 *
 * @package CKL_Car_Rental
 */

$current_user = wp_get_current_user();
?>

<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">
                    <?php printf(__('Welcome, %s!', 'ckl-car-rental'), esc_html($current_user->display_name)); ?>
                </h1>
                <p class="text-gray-600 mt-2"><?php _e('Manage your bookings and profile', 'ckl-car-rental'); ?></p>
            </div>
            <div class="text-right">
                <span class="inline-block bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">
                    <?php _e('Renter', 'ckl-car-rental'); ?>
                </span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md p-6">
                <nav class="space-y-2">
                    <a href="#my-bookings" class="block px-4 py-2 rounded bg-blue-600 text-white">
                        <?php _e('My Bookings', 'ckl-car-rental'); ?>
                    </a>
                    <a href="#my-profile" class="block px-4 py-2 rounded hover:bg-gray-100">
                        <?php _e('My Profile', 'ckl-car-rental'); ?>
                    </a>
                    <a href="#my-reviews" class="block px-4 py-2 rounded hover:bg-gray-100">
                        <?php _e('My Reviews', 'ckl-car-rental'); ?>
                    </a>
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="block px-4 py-2 rounded hover:bg-gray-100">
                        <?php _e('Browse Vehicles', 'ckl-car-rental'); ?>
                    </a>
                </nav>
            </div>
        </div>

        <!-- Main Content -->
        <div class="lg:col-span-3 space-y-8">
            <!-- My Bookings Section -->
            <div id="my-bookings" class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-6"><?php _e('My Bookings', 'ckl-car-rental'); ?></h2>

                <?php
                // Get user's bookings
                $bookings = get_posts(array(
                    'post_type' => 'wc_booking',
                    'posts_per_page' => 10,
                    'author' => $current_user->ID,
                ));

                if (!empty($bookings)) :
                ?>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <?php _e('Vehicle', 'ckl-car-rental'); ?>
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <?php _e('Dates', 'ckl-car-rental'); ?>
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <?php _e('Status', 'ckl-car-rental'); ?>
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <?php _e('Total', 'ckl-car-rental'); ?>
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <?php _e('Actions', 'ckl-car-rental'); ?>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($bookings as $booking) :
                                    $booking_obj = get_wc_booking($booking->ID);
                                    $product = $booking_obj->get_product();
                                    $status = $booking_obj->get_status();
                                ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                <?php echo esc_html($product->get_name()); ?>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                <?php echo esc_html($booking_obj->get_start_date()); ?>
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                <?php echo esc_html($booking_obj->get_end_date()); ?>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <?php
                                            $status_classes = array(
                                                'confirmed' => 'bg-green-100 text-green-800',
                                                'pending' => 'bg-yellow-100 text-yellow-800',
                                                'cancelled' => 'bg-red-100 text-red-800',
                                                'completed' => 'bg-blue-100 text-blue-800',
                                            );
                                            $status_class = isset($status_classes[$status]) ? $status_classes[$status] : 'bg-gray-100 text-gray-800';
                                            ?>
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo esc_attr($status_class); ?>">
                                                <?php echo esc_html(ucfirst($status)); ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <?php echo wc_price($booking_obj->get_total()); ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="<?php echo esc_url($booking_obj->get_view_booking_url()); ?>" class="text-blue-600 hover:text-blue-900">
                                                <?php _e('View', 'ckl-car-rental'); ?>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else : ?>
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900"><?php _e('No bookings', 'ckl-car-rental'); ?></h3>
                        <p class="mt-1 text-sm text-gray-500"><?php _e('Get started by browsing our vehicles.', 'ckl-car-rental'); ?></p>
                        <div class="mt-6">
                            <a href="<?php echo esc_url(home_url('/vehicles/')); ?>" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                <?php _e('Browse Vehicles', 'ckl-car-rental'); ?>
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- My Profile Section -->
            <div id="my-profile" class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-6"><?php _e('My Profile', 'ckl-car-rental'); ?></h2>

                <form id="profile-form" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700">
                                <?php _e('First Name', 'ckl-car-rental'); ?>
                            </label>
                            <input type="text" id="first_name" name="first_name" value="<?php echo esc_attr($current_user->first_name); ?>"
                                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        </div>

                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700">
                                <?php _e('Last Name', 'ckl-car-rental'); ?>
                            </label>
                            <input type="text" id="last_name" name="last_name" value="<?php echo esc_attr($current_user->last_name); ?>"
                                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">
                                <?php _e('Email', 'ckl-car-rental'); ?>
                            </label>
                            <input type="email" id="email" name="email" value="<?php echo esc_attr($current_user->user_email); ?>"
                                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700">
                                <?php _e('Phone', 'ckl-car-rental'); ?>
                            </label>
                            <input type="tel" id="phone" name="phone" value="<?php echo esc_attr(get_user_meta($current_user->ID, 'billing_phone', true)); ?>"
                                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <?php _e('Save Changes', 'ckl-car-rental'); ?>
                        </button>
                    </div>
                </form>
            </div>

            <!-- My Reviews Section -->
            <div id="my-reviews" class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-6"><?php _e('My Reviews', 'ckl-car-rental'); ?></h2>

                <?php
                $reviews = get_comments(array(
                    'user_id' => $current_user->ID,
                    'post_type' => 'vehicle',
                    'number' => 10,
                ));

                if (!empty($reviews)) :
                ?>
                    <div class="space-y-6">
                        <?php foreach ($reviews as $review) :
                            $rating = get_comment_meta($review->comment_ID, 'rating', true);
                            $vehicle = get_post($review->comment_post_ID);
                        ?>
                            <div class="border-b border-gray-200 pb-6">
                                <div class="flex items-center justify-between mb-2">
                                    <h3 class="text-lg font-medium text-gray-900">
                                        <?php echo esc_html($vehicle->post_title); ?>
                                    </h3>
                                    <div class="flex items-center">
                                        <?php for ($i = 1; $i <= 5; $i++) : ?>
                                            <svg class="w-5 h-5 <?php echo $i <= $rating ? 'text-yellow-400' : 'text-gray-300'; ?>" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                                <p class="text-gray-700"><?php echo esc_html($review->comment_content); ?></p>
                                <p class="text-sm text-gray-500 mt-2">
                                    <?php echo esc_html(date_i18n(get_option('date_format'), strtotime($review->comment_date))); ?>
                                </p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else : ?>
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900"><?php _e('No reviews yet', 'ckl-car-rental'); ?></h3>
                        <p class="mt-1 text-sm text-gray-500"><?php _e('Complete a booking to leave a review.', 'ckl-car-rental'); ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
