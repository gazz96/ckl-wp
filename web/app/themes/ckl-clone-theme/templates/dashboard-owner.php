<?php
/**
 * Owner Dashboard Template
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
                <p class="text-gray-600 mt-2"><?php _e('Manage your vehicles and bookings', 'ckl-car-rental'); ?></p>
            </div>
            <div class="text-right">
                <span class="inline-block bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">
                    <?php _e('Vehicle Owner', 'ckl-car-rental'); ?>
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
                    <p class="text-sm font-medium text-gray-600"><?php _e('Total Vehicles', 'ckl-car-rental'); ?></p>
                    <p class="text-2xl font-semibold text-gray-900">
                        <?php
                        $vehicles = get_posts(array(
                            'post_type' => 'vehicle',
                            'author' => $current_user->ID,
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
                <div class="flex-shrink-0 bg-green-100 rounded-md p-3">
                    <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600"><?php _e('Active Bookings', 'ckl-car-rental'); ?></p>
                    <p class="text-2xl font-semibold text-gray-900">
                        <?php
                        $active_bookings = get_posts(array(
                            'post_type' => 'wc_booking',
                            'posts_per_page' => -1,
                            'post_status' => array('confirmed', 'paid'),
                        ));
                        echo count($active_bookings);
                        ?>
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-yellow-100 rounded-md p-3">
                    <svg class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600"><?php _e('Monthly Revenue', 'ckl-car-rental'); ?></p>
                    <p class="text-2xl font-semibold text-gray-900">
                        <?php
                        // This would calculate actual revenue
                        echo 'RM ' . number_format(0, 2);
                        ?>
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-purple-100 rounded-md p-3">
                    <svg class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600"><?php _e('Avg. Rating', 'ckl-car-rental'); ?></p>
                    <p class="text-2xl font-semibold text-gray-900">
                        <?php
                        // This would calculate actual average rating
                        echo '0.0';
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
                    <a href="#my-vehicles" class="block px-4 py-2 rounded bg-green-600 text-white">
                        <?php _e('My Vehicles', 'ckl-car-rental'); ?>
                    </a>
                    <a href="#vehicle-bookings" class="block px-4 py-2 rounded hover:bg-gray-100">
                        <?php _e('Vehicle Bookings', 'ckl-car-rental'); ?>
                    </a>
                    <a href="#add-vehicle" class="block px-4 py-2 rounded hover:bg-gray-100">
                        <?php _e('Add New Vehicle', 'ckl-car-rental'); ?>
                    </a>
                    <a href="#vehicle-reviews" class="block px-4 py-2 rounded hover:bg-gray-100">
                        <?php _e('Vehicle Reviews', 'ckl-car-rental'); ?>
                    </a>
                    <a href="#vehicle-analytics" class="block px-4 py-2 rounded hover:bg-gray-100">
                        <?php _e('Analytics', 'ckl-car-rental'); ?>
                    </a>
                </nav>
            </div>
        </div>

        <!-- Main Content -->
        <div class="lg:col-span-3 space-y-8">
            <!-- My Vehicles Section -->
            <div id="my-vehicles" class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-900"><?php _e('My Vehicles', 'ckl-car-rental'); ?></h2>
                    <a href="<?php echo esc_url(admin_url('post-new.php?post_type=vehicle')); ?>" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                        <?php _e('Add New Vehicle', 'ckl-car-rental'); ?>
                    </a>
                </div>

                <?php if (!empty($vehicles)) : ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <?php foreach ($vehicles as $vehicle) :
                            $price = get_post_meta($vehicle->ID, '_vehicle_price_per_day', true);
                            $type = get_post_meta($vehicle->ID, '_vehicle_type', true);
                            $status = get_post_meta($vehicle->ID, '_vehicle_sync_status', true);
                        ?>
                            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                                <?php if (has_post_thumbnail($vehicle->ID)) : ?>
                                    <div class="aspect-w-16 aspect-h-9 mb-4">
                                        <?php echo get_the_post_thumbnail($vehicle->ID, 'medium', array('class' => 'w-full h-48 object-cover rounded')); ?>
                                    </div>
                                <?php endif; ?>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">
                                    <a href="<?php echo esc_url(get_permalink($vehicle->ID)); ?>">
                                        <?php echo esc_html($vehicle->post_title); ?>
                                    </a>
                                </h3>
                                <div class="flex items-center justify-between text-sm text-gray-600">
                                    <span><?php echo esc_html(ucfirst($type)); ?></span>
                                    <span class="font-semibold text-gray-900">RM <?php echo esc_html(number_format($price, 2)); ?>/day</span>
                                </div>
                                <div class="mt-4 flex items-center justify-between">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo $status === 'success' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'; ?>">
                                        <?php echo $status === 'success' ? __('Synced', 'ckl-car-rental') : __('Pending Sync', 'ckl-car-rental'); ?>
                                    </span>
                                    <div class="space-x-2">
                                        <a href="<?php echo esc_url(get_edit_post_link($vehicle->ID)); ?>" class="text-blue-600 hover:text-blue-900 text-sm">
                                            <?php _e('Edit', 'ckl-car-rental'); ?>
                                        </a>
                                        <a href="<?php echo esc_url(get_permalink($vehicle->ID)); ?>" class="text-gray-600 hover:text-gray-900 text-sm">
                                            <?php _e('View', 'ckl-car-rental'); ?>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else : ?>
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900"><?php _e('No vehicles', 'ckl-car-rental'); ?></h3>
                        <p class="mt-1 text-sm text-gray-500"><?php _e('Get started by adding your first vehicle.', 'ckl-car-rental'); ?></p>
                        <div class="mt-6">
                            <a href="<?php echo esc_url(admin_url('post-new.php?post_type=vehicle')); ?>" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                                <?php _e('Add Vehicle', 'ckl-car-rental'); ?>
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Recent Bookings Section -->
            <div id="vehicle-bookings" class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-6"><?php _e('Recent Vehicle Bookings', 'ckl-car-rental'); ?></h2>

                <?php
                // Get bookings for owner's vehicles
                $vehicle_ids = wp_list_pluck($vehicles, 'ID');

                if (!empty($vehicle_ids)) :
                    $bookings = get_posts(array(
                        'post_type' => 'wc_booking',
                        'posts_per_page' => 10,
                        'meta_query' => array(
                            array(
                                'key' => '_vehicle_id',
                                'value' => $vehicle_ids,
                                'compare' => 'IN',
                            ),
                        ),
                    ));
                ?>

                    <?php if (!empty($bookings)) : ?>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
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
                                    <?php foreach ($bookings as $booking) :
                                        $booking_obj = get_wc_booking($booking->ID);
                                        $product = $booking_obj->get_product();
                                        $customer = $booking_obj->get_customer();
                                        $status = $booking_obj->get_status();
                                    ?>
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">
                                                    <?php echo esc_html($product->get_name()); ?>
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
                    <?php else : ?>
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900"><?php _e('No bookings yet', 'ckl-car-rental'); ?></h3>
                            <p class="mt-1 text-sm text-gray-500"><?php _e('Bookings will appear here once customers start renting your vehicles.', 'ckl-car-rental'); ?></p>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
