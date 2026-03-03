<?php
/**
 * Administrator Dashboard Template
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
                <p class="text-gray-600 mt-2"><?php _e('System overview and analytics', 'ckl-car-rental'); ?></p>
            </div>
            <div class="text-right">
                <span class="inline-block bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-sm font-medium">
                    <?php _e('Administrator', 'ckl-car-rental'); ?>
                </span>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-blue-100 rounded-md p-3">
                    <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600"><?php _e('Total Bookings', 'ckl-car-rental'); ?></p>
                    <p class="text-2xl font-semibold text-gray-900">
                        <?php
                        $bookings = get_posts(array(
                            'post_type' => 'wc_booking',
                            'posts_per_page' => -1,
                        ));
                        echo count($bookings);
                        ?>
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-green-100 rounded-md p-3">
                    <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600"><?php _e('Total Revenue', 'ckl-car-rental'); ?></p>
                    <p class="text-2xl font-semibold text-gray-900">
                        <?php
                        // Calculate total revenue from bookings
                        $total_revenue = 0;
                        foreach ($bookings as $booking) {
                            $booking_obj = get_wc_booking($booking->ID);
                            if ($booking_obj) {
                                $total_revenue += $booking_obj->get_total();
                            }
                        }
                        echo 'RM ' . number_format($total_revenue, 2);
                        ?>
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-yellow-100 rounded-md p-3">
                    <svg class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600"><?php _e('Total Vehicles', 'ckl-car-rental'); ?></p>
                    <p class="text-2xl font-semibold text-gray-900">
                        <?php
                        $vehicles = get_posts(array(
                            'post_type' => 'vehicle',
                            'posts_per_page' => -1,
                        ));
                        echo count($vehicles);
                        ?>
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-purple-100 rounded-md p-3">
                    <svg class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600"><?php _e('Total Users', 'ckl-car-rental'); ?></p>
                    <p class="text-2xl font-semibold text-gray-900">
                        <?php
                        $user_count = count_users();
                        echo $user_count['total_users'];
                        ?>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md p-6">
                <nav class="space-y-2">
                    <a href="#overview" class="block px-4 py-2 rounded bg-purple-600 text-white">
                        <?php _e('Overview', 'ckl-car-rental'); ?>
                    </a>
                    <a href="#all-bookings" class="block px-4 py-2 rounded hover:bg-gray-100">
                        <?php _e('All Bookings', 'ckl-car-rental'); ?>
                    </a>
                    <a href="#all-vehicles" class="block px-4 py-2 rounded hover:bg-gray-100">
                        <?php _e('All Vehicles', 'ckl-car-rental'); ?>
                    </a>
                    <a href="#all-users" class="block px-4 py-2 rounded hover:bg-gray-100">
                        <?php _e('All Users', 'ckl-car-rental'); ?>
                    </a>
                    <a href="#analytics" class="block px-4 py-2 rounded hover:bg-gray-100">
                        <?php _e('Analytics', 'ckl-car-rental'); ?>
                    </a>
                    <a href="#settings" class="block px-4 py-2 rounded hover:bg-gray-100">
                        <?php _e('Settings', 'ckl-car-rental'); ?>
                    </a>
                </nav>
            </div>
        </div>

        <!-- Main Content -->
        <div class="lg:col-span-3 space-y-8">
            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-6"><?php _e('Quick Actions', 'ckl-car-rental'); ?></h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <a href="<?php echo esc_url(admin_url('post-new.php?post_type=vehicle')); ?>" class="flex items-center p-4 border border-gray-200 rounded-lg hover:shadow-md transition">
                        <div class="flex-shrink-0 bg-blue-100 rounded-md p-2">
                            <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900"><?php _e('Add Vehicle', 'ckl-car-rental'); ?></p>
                        </div>
                    </a>

                    <a href="<?php echo esc_url(admin_url('edit.php?post_type=vehicle')); ?>" class="flex items-center p-4 border border-gray-200 rounded-lg hover:shadow-md transition">
                        <div class="flex-shrink-0 bg-green-100 rounded-md p-2">
                            <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900"><?php _e('Manage Vehicles', 'ckl-car-rental'); ?></p>
                        </div>
                    </a>

                    <a href="<?php echo esc_url(admin_url('edit.php?post_type=shop_order')); ?>" class="flex items-center p-4 border border-gray-200 rounded-lg hover:shadow-md transition">
                        <div class="flex-shrink-0 bg-yellow-100 rounded-md p-2">
                            <svg class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900"><?php _e('View Orders', 'ckl-car-rental'); ?></p>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Recent Bookings -->
            <div id="all-bookings" class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-900"><?php _e('Recent Bookings', 'ckl-car-rental'); ?></h2>
                    <a href="<?php echo esc_url(admin_url('edit.php?post_type=wc_booking')); ?>" class="text-blue-600 hover:text-blue-900 text-sm font-medium">
                        <?php _e('View All', 'ckl-car-rental'); ?> →
                    </a>
                </div>

                <?php if (!empty($bookings)) : ?>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <?php _e('ID', 'ckl-car-rental'); ?>
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <?php _e('Vehicle', 'ckl-car-rental'); ?>
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <?php _e('Customer', 'ckl-car-rental'); ?>
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
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php
                                $recent_bookings = array_slice($bookings, 0, 10);
                                foreach ($recent_bookings as $booking) :
                                    $booking_obj = get_wc_booking($booking->ID);
                                    if (!$booking_obj) continue;

                                    $product = $booking_obj->get_product();
                                    $customer = $booking_obj->get_customer();
                                    $status = $booking_obj->get_status();
                                ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            #<?php echo esc_html($booking->ID); ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                <?php echo esc_html($product ? $product->get_name() : 'N/A'); ?>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900"><?php echo esc_html($customer->name); ?></div>
                                            <div class="text-sm text-gray-500"><?php echo esc_html($customer->email); ?></div>
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
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>

            <!-- System Health -->
            <div id="overview" class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-6"><?php _e('System Health', 'ckl-car-rental'); ?></h2>

                <div class="space-y-4">
                    <div class="flex items-center justify-between p-4 bg-green-50 rounded-lg">
                        <div class="flex items-center">
                            <svg class="h-5 w-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <span class="ml-3 text-sm font-medium text-gray-900"><?php _e('WooCommerce', 'ckl-car-rental'); ?></span>
                        </div>
                        <span class="text-sm text-green-600"><?php _e('Active', 'ckl-car-rental'); ?></span>
                    </div>

                    <div class="flex items-center justify-between p-4 bg-green-50 rounded-lg">
                        <div class="flex items-center">
                            <svg class="h-5 w-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <span class="ml-3 text-sm font-medium text-gray-900"><?php _e('WooCommerce Bookings', 'ckl-car-rental'); ?></span>
                        </div>
                        <span class="text-sm text-green-600"><?php _e('Active', 'ckl-car-rental'); ?></span>
                    </div>

                    <div class="flex items-center justify-between p-4 bg-green-50 rounded-lg">
                        <div class="flex items-center">
                            <svg class="h-5 w-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <span class="ml-3 text-sm font-medium text-gray-900"><?php _e('CKL Car Rental Plugin', 'ckl-car-rental'); ?></span>
                        </div>
                        <span class="text-sm text-green-600"><?php _e('Active', 'ckl-car-rental'); ?></span>
                    </div>

                    <div class="flex items-center justify-between p-4 bg-green-50 rounded-lg">
                        <div class="flex items-center">
                            <svg class="h-5 w-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <span class="ml-3 text-sm font-medium text-gray-900"><?php _e('Theme', 'ckl-car-rental'); ?></span>
                        </div>
                        <span class="text-sm text-green-600"><?php echo esc_html(wp_get_theme()->get('Name')); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
