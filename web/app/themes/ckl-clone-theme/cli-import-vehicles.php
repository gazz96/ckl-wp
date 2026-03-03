<?php
/**
 * CLI Vehicle Import - Simplified Version
 *
 * Usage: wp eval-file cli-import-vehicles.php
 */

if (!defined('ABSPATH')) {
    $wp_load_paths = array(
        __DIR__ . '/../../../wp-load.php',
        __DIR__ . '/../../../../wp-load.php',
    );

    foreach ($wp_load_paths as $path) {
        if (file_exists($path)) {
            require_once($path);
            break;
        }
    }
}

$vehicles_data = array(
    array('id' => '12f78c51', 'name' => 'Honda', 'model' => 'CRV', 'price_per_day' => 300, 'image_url' => 'https://htlqqgdaevwmyybaykxx.supabase.co/storage/v1/object/public/car-images/cars/1767927992928-cykytf.jpeg', 'seats' => 5, 'transmission' => 'automatic', 'fuel_type' => 'petrol', 'car_type' => 'suv', 'doors' => 4, 'luggage' => 2, 'has_air_condition' => true, 'plate_number' => '-', 'total_units' => 1, 'is_active' => true, 'late_fee_per_hour' => 20, 'rating' => 0, 'status' => 'available', 'total_bookings' => 0, 'grace_period_minutes' => 120),
    array('id' => 'a61581ab', 'name' => 'Honda', 'model' => 'ADV160', 'price_per_day' => 80, 'image_url' => 'https://htlqqgdaevwmyybaykxx.supabase.co/storage/v1/object/public/car-images/cars/1766402224544-bnlhce.png', 'seats' => 2, 'transmission' => 'automatic', 'fuel_type' => 'petrol', 'car_type' => 'motorcycle', 'doors' => 0, 'luggage' => 0, 'has_air_condition' => false, 'plate_number' => 'HON0020', 'total_units' => 5, 'is_active' => true, 'late_fee_per_hour' => 10, 'rating' => 4.5, 'status' => 'available', 'total_bookings' => 0),
    array('id' => '2b662aa7', 'name' => 'Toyota', 'model' => 'Vios', 'price_per_day' => 110, 'image_url' => 'https://htlqqgdaevwmyybaykxx.supabase.co/storage/v1/object/public/car-images/cars/1766402438169-cj60xq.png', 'seats' => 5, 'transmission' => 'automatic', 'fuel_type' => 'petrol', 'car_type' => 'sedan', 'doors' => 4, 'luggage' => 2, 'has_air_condition' => true, 'plate_number' => 'TOY0001', 'total_units' => 7, 'is_active' => true, 'late_fee_per_hour' => 10, 'rating' => 4.5, 'status' => 'available', 'total_bookings' => 0),
    array('id' => '05a5e661', 'name' => 'Perodua', 'model' => 'Axia', 'price_per_day' => 70, 'image_url' => 'https://htlqqgdaevwmyybaykxx.supabase.co/storage/v1/object/public/car-images/cars/1766403192757-5tbala.png', 'seats' => 4, 'transmission' => 'automatic', 'fuel_type' => 'petrol', 'car_type' => 'compact', 'doors' => 4, 'luggage' => 2, 'has_air_condition' => true, 'plate_number' => 'PER0003', 'total_units' => 12, 'is_active' => true, 'late_fee_per_hour' => 10, 'rating' => 4.5, 'status' => 'available', 'total_bookings' => 0),
    array('id' => '8c50259f', 'name' => 'Perodua', 'model' => 'Myvi', 'price_per_day' => 80, 'image_url' => 'https://htlqqgdaevwmyybaykxx.supabase.co/storage/v1/object/public/car-images/cars/1766402324839-h2flej.png', 'seats' => 4, 'transmission' => 'automatic', 'fuel_type' => 'petrol', 'car_type' => 'compact', 'doors' => 4, 'luggage' => 2, 'has_air_condition' => true, 'plate_number' => 'PER0004', 'total_units' => 6, 'is_active' => true, 'late_fee_per_hour' => 10, 'rating' => 4.5, 'status' => 'available', 'total_bookings' => 0),
    array('id' => '2108e2df', 'name' => 'Nissan', 'model' => 'Almera Turbo', 'price_per_day' => 120, 'image_url' => 'https://htlqqgdaevwmyybaykxx.supabase.co/storage/v1/object/public/car-images/cars/1766401990671-8rh3bv.png', 'seats' => 5, 'transmission' => 'automatic', 'fuel_type' => 'petrol', 'car_type' => 'sedan', 'doors' => 4, 'luggage' => 2, 'has_air_condition' => true, 'plate_number' => 'NIS0006', 'total_units' => 5, 'is_active' => true, 'late_fee_per_hour' => 10, 'rating' => 4.5, 'status' => 'available', 'total_bookings' => 0),
    array('id' => '3b12a698', 'name' => 'Honda', 'model' => 'City', 'price_per_day' => 120, 'image_url' => 'https://htlqqgdaevwmyybaykxx.supabase.co/storage/v1/object/public/car-images/cars/1766401444112-76tfyx.png', 'seats' => 5, 'transmission' => 'automatic', 'fuel_type' => 'petrol', 'car_type' => 'sedan', 'doors' => 4, 'luggage' => 2, 'has_air_condition' => true, 'plate_number' => 'HON0007', 'total_units' => 5, 'is_active' => true, 'late_fee_per_hour' => 10, 'rating' => 4.5, 'status' => 'available', 'total_bookings' => 0),
    array('id' => '78ae46a9', 'name' => 'Perodua', 'model' => 'Alza', 'price_per_day' => 130, 'image_url' => 'https://htlqqgdaevwmyybaykxx.supabase.co/storage/v1/object/public/car-images/cars/1766401344406-cu5ias.png', 'seats' => 7, 'transmission' => 'automatic', 'fuel_type' => 'petrol', 'car_type' => 'mpv', 'doors' => 4, 'luggage' => 2, 'has_air_condition' => true, 'plate_number' => 'PER0002', 'total_units' => 5, 'is_active' => true, 'late_fee_per_hour' => 15, 'rating' => 4.5, 'status' => 'available', 'total_bookings' => 0),
    array('id' => '3564c24e', 'name' => 'Toyota', 'model' => 'Avanza', 'price_per_day' => 120, 'image_url' => 'https://htlqqgdaevwmyybaykxx.supabase.co/storage/v1/object/public/car-images/cars/1766401897840-zilpuj.png', 'seats' => 7, 'transmission' => 'automatic', 'fuel_type' => 'petrol', 'car_type' => 'mpv', 'doors' => 4, 'luggage' => 2, 'has_air_condition' => true, 'plate_number' => 'TOY0009', 'total_units' => 5, 'is_active' => true, 'late_fee_per_hour' => 15, 'rating' => 4.5, 'status' => 'available', 'total_bookings' => 0),
    array('id' => 'b81f0bde', 'name' => 'Toyota', 'model' => 'Innova', 'price_per_day' => 180, 'image_url' => 'https://htlqqgdaevwmyybaykxx.supabase.co/storage/v1/object/public/car-images/cars/1766401006157-p6edp.png', 'seats' => 8, 'transmission' => 'automatic', 'fuel_type' => 'petrol', 'car_type' => 'mpv', 'doors' => 4, 'luggage' => 2, 'has_air_condition' => true, 'plate_number' => 'TOY0010', 'total_units' => 5, 'is_active' => true, 'late_fee_per_hour' => 15, 'rating' => 4.5, 'status' => 'available', 'total_bookings' => 0),
    array('id' => '0e83986e', 'name' => 'Nissan', 'model' => 'Serena', 'price_per_day' => 180, 'image_url' => 'https://htlqqgdaevwmyybaykxx.supabase.co/storage/v1/object/public/car-images/cars/1766401726832-8c2vyc.png', 'seats' => 7, 'transmission' => 'automatic', 'fuel_type' => 'petrol', 'car_type' => 'mpv', 'doors' => 4, 'luggage' => 2, 'has_air_condition' => true, 'plate_number' => 'NIS0011', 'total_units' => 6, 'is_active' => true, 'late_fee_per_hour' => 15, 'rating' => 4.5, 'status' => 'available', 'total_bookings' => 0),
    array('id' => 'ac9c13f6', 'name' => 'Nissan', 'model' => 'Urvan', 'price_per_day' => 200, 'image_url' => 'https://htlqqgdaevwmyybaykxx.supabase.co/storage/v1/object/public/car-images/cars/1766400729754-r208q.png', 'seats' => 15, 'transmission' => 'manual', 'fuel_type' => 'diesel', 'car_type' => 'mpv', 'doors' => 4, 'luggage' => 2, 'has_air_condition' => true, 'plate_number' => 'NIS0017', 'total_units' => 5, 'is_active' => true, 'late_fee_per_hour' => 20, 'rating' => 4.5, 'status' => 'available', 'total_bookings' => 0),
    array('id' => '8295273e', 'name' => 'KTM', 'model' => '1290 SuperAdventure R', 'price_per_day' => 600, 'image_url' => 'https://htlqqgdaevwmyybaykxx.supabase.co/storage/v1/object/public/car-images/cars/1766401173435-sn5etp.png', 'seats' => 2, 'transmission' => 'manual', 'fuel_type' => 'petrol', 'car_type' => 'motorcycle', 'doors' => 0, 'luggage' => 0, 'has_air_condition' => false, 'plate_number' => 'KTM0022', 'total_units' => 1, 'is_active' => true, 'late_fee_per_hour' => 20, 'rating' => 4.5, 'status' => 'available', 'total_bookings' => 0),
    array('id' => '85fdfb6d', 'name' => 'Toyota', 'model' => 'Camry', 'price_per_day' => 350, 'image_url' => 'https://htlqqgdaevwmyybaykxx.supabase.co/storage/v1/object/public/car-images/cars/1766402116232-59n22i.png', 'seats' => 5, 'transmission' => 'automatic', 'fuel_type' => 'petrol', 'car_type' => 'sedan', 'doors' => 4, 'luggage' => 2, 'has_air_condition' => true, 'plate_number' => 'TOY0008', 'total_units' => 5, 'is_active' => true, 'late_fee_per_hour' => 30, 'rating' => 4.5, 'status' => 'available', 'total_bookings' => 0),
    array('id' => '5fe4d51e', 'name' => 'Hyundai', 'model' => 'Staria', 'price_per_day' => 350, 'image_url' => 'https://htlqqgdaevwmyybaykxx.supabase.co/storage/v1/object/public/car-images/cars/1766402905006-yuzjnj.png', 'seats' => 11, 'transmission' => 'automatic', 'fuel_type' => 'diesel', 'car_type' => 'luxury_mpv', 'doors' => 4, 'luggage' => 2, 'has_air_condition' => true, 'plate_number' => 'HYU0013', 'total_units' => 4, 'is_active' => true, 'late_fee_per_hour' => 30, 'rating' => 4.5, 'status' => 'available', 'total_bookings' => 0),
    array('id' => '17d514a8', 'name' => 'Toyota', 'model' => 'Vellfire', 'price_per_day' => 350, 'image_url' => 'https://htlqqgdaevwmyybaykxx.supabase.co/storage/v1/object/public/car-images/cars/1766403075145-t4tfpk.png', 'seats' => 7, 'transmission' => 'automatic', 'fuel_type' => 'petrol', 'car_type' => 'luxury_mpv', 'doors' => 4, 'luggage' => 2, 'has_air_condition' => true, 'plate_number' => 'TOY0014', 'total_units' => 5, 'is_active' => true, 'late_fee_per_hour' => 30, 'rating' => 4.5, 'status' => 'available', 'total_bookings' => 0),
    array('id' => '8fa46c88', 'name' => 'Toyota', 'model' => 'Alphard', 'price_per_day' => 380, 'image_url' => 'https://htlqqgdaevwmyybaykxx.supabase.co/storage/v1/object/public/car-images/cars/1766402607963-t2t6fk.png', 'seats' => 7, 'transmission' => 'automatic', 'fuel_type' => 'petrol', 'car_type' => 'luxury_mpv', 'doors' => 4, 'luggage' => 2, 'has_air_condition' => true, 'plate_number' => 'TOY0015', 'total_units' => 7, 'is_active' => true, 'late_fee_per_hour' => 30, 'rating' => 4.5, 'status' => 'available', 'total_bookings' => 0),
    array('id' => 'dcddf543', 'name' => 'Perodua', 'model' => 'Bezza', 'price_per_day' => 90, 'image_url' => 'https://htlqqgdaevwmyybaykxx.supabase.co/storage/v1/object/public/car-images/cars/1766401617346-ms1rv.png', 'seats' => 5, 'transmission' => 'automatic', 'fuel_type' => 'petrol', 'car_type' => 'sedan', 'doors' => 4, 'luggage' => 2, 'has_air_condition' => true, 'plate_number' => 'PER0005', 'total_units' => 10, 'is_active' => true, 'late_fee_per_hour' => 10, 'rating' => 4.5, 'status' => 'available', 'total_bookings' => 0),
    array('id' => 'c5397bf2', 'name' => 'Mitsubishi', 'model' => 'Triton', 'price_per_day' => 350, 'image_url' => 'https://htlqqgdaevwmyybaykxx.supabase.co/storage/v1/object/public/car-images/cars/1766401259573-kbzw6g.png', 'seats' => 5, 'transmission' => 'automatic', 'fuel_type' => 'diesel', 'car_type' => '4x4', 'doors' => 4, 'luggage' => 2, 'has_air_condition' => true, 'plate_number' => 'MIT0018', 'total_units' => 3, 'is_active' => true, 'late_fee_per_hour' => 20, 'rating' => 4.5, 'status' => 'available', 'total_bookings' => 0),
    array('id' => 'dbe50e00', 'name' => 'Hyundai', 'model' => 'Starex', 'price_per_day' => 330, 'image_url' => 'https://htlqqgdaevwmyybaykxx.supabase.co/storage/v1/object/public/car-images/cars/1766402797264-lha4hh.png', 'seats' => 11, 'transmission' => 'automatic', 'fuel_type' => 'diesel', 'car_type' => 'luxury_mpv', 'doors' => 4, 'luggage' => 2, 'has_air_condition' => true, 'plate_number' => 'HYU0012', 'total_units' => 4, 'is_active' => true, 'late_fee_per_hour' => 30, 'rating' => 4.5, 'status' => 'available', 'total_bookings' => 0),
    array('id' => 'bdcb3f36', 'name' => 'Toyota', 'model' => 'Vellfire AGH 40', 'price_per_day' => 800, 'image_url' => 'https://htlqqgdaevwmyybaykxx.supabase.co/storage/v1/object/public/car-images/cars/1767884686785-itkqep.jpeg', 'seats' => 7, 'transmission' => 'automatic', 'fuel_type' => 'petrol', 'car_type' => 'luxury_mpv', 'doors' => 4, 'luggage' => 2, 'has_air_condition' => true, 'plate_number' => 'TOY0016', 'total_units' => 3, 'is_active' => true, 'late_fee_per_hour' => 50, 'rating' => 4.5, 'status' => 'available', 'total_bookings' => 0),
    array('id' => 'a40397c3', 'name' => 'Yamaha', 'model' => 'NVX', 'price_per_day' => 70, 'image_url' => 'https://htlqqgdaevwmyybaykxx.supabase.co/storage/v1/object/public/car-images/cars/1766402513265-f1y8gk.png', 'seats' => 2, 'transmission' => 'automatic', 'fuel_type' => 'petrol', 'car_type' => 'motorcycle', 'doors' => 0, 'luggage' => 0, 'has_air_condition' => false, 'plate_number' => 'YAM0021', 'total_units' => 3, 'is_active' => true, 'late_fee_per_hour' => 0, 'rating' => 4.5, 'status' => 'available', 'total_bookings' => 0),
    array('id' => 'c211acee', 'name' => 'Mini Cooper', 'model' => 'Convertible', 'price_per_day' => 600, 'image_url' => 'https://htlqqgdaevwmyybaykxx.supabase.co/storage/v1/object/public/car-images/cars/1766402688625-tgvaf.png', 'seats' => 4, 'transmission' => 'automatic', 'fuel_type' => 'petrol', 'car_type' => 'compact', 'doors' => 4, 'luggage' => 2, 'has_air_condition' => true, 'plate_number' => 'MIN0019', 'total_units' => 3, 'is_active' => true, 'late_fee_per_hour' => 50, 'rating' => 4.5, 'status' => 'available', 'total_bookings' => 0),
);

WP_CLI::log('Starting vehicle import...');
WP_CLI::log('Total vehicles to import: ' . count($vehicles_data));

$imported = 0;
$skipped = 0;

foreach ($vehicles_data as $data) {
    $existing = get_posts(array(
        'post_type' => 'vehicle',
        'meta_query' => array(array('key' => '_supabase_id', 'value' => $data['id'])),
        'posts_per_page' => 1
    ));

    if (!empty($existing)) {
        WP_CLI::log("Skipping: {$data['name']} {$data['model']} (already exists)");
        $skipped++;
        continue;
    }

    $title = $data['name'] . ' ' . $data['model'];
    $content = "<h2>{$title}</h2><p>Rent this " . ucfirst($data['car_type']) . " in Langkawi. Features {$data['seats']} seats, " . ucfirst($data['transmission']) . " transmission.</p>";

    $vehicle_id = wp_insert_post(array(
        'post_title' => $title,
        'post_content' => $content,
        'post_status' => 'publish',
        'post_type' => 'vehicle',
    ));

    if (is_wp_error($vehicle_id)) {
        WP_CLI::warning("Error creating {$title}: " . $vehicle_id->get_error_message());
        continue;
    }

    update_post_meta($vehicle_id, '_supabase_id', $data['id']);
    update_post_meta($vehicle_id, '_vehicle_type', $data['car_type']);
    update_post_meta($vehicle_id, '_vehicle_passenger_capacity', $data['seats']);
    update_post_meta($vehicle_id, '_vehicle_doors', $data['doors']);
    update_post_meta($vehicle_id, '_vehicle_luggage', $data['luggage']);
    update_post_meta($vehicle_id, '_vehicle_has_air_conditioning', $data['has_air_condition'] ? '1' : '0');
    update_post_meta($vehicle_id, '_vehicle_transmission', $data['transmission']);
    update_post_meta($vehicle_id, '_vehicle_fuel_type', $data['fuel_type']);
    update_post_meta($vehicle_id, '_vehicle_plate_number', $data['plate_number']);
    update_post_meta($vehicle_id, '_vehicle_units_available', $data['total_units']);
    update_post_meta($vehicle_id, '_vehicle_price_per_day', $data['price_per_day']);
    update_post_meta($vehicle_id, '_vehicle_late_fee_per_hour', $data['late_fee_per_hour']);

    // Download image
    require_once(ABSPATH . 'wp-admin/includes/file.php');
    require_once(ABSPATH . 'wp-admin/includes/media.php');
    $tmp = download_url($data['image_url']);
    if (!is_wp_error($tmp)) {
        $file_array = array('name' => basename($data['image_url']), 'tmp_name' => $tmp);
        $img_id = media_handle_sideload($file_array, 0);
        @unlink($tmp);
        if (!is_wp_error($img_id)) {
            set_post_thumbnail($vehicle_id, $img_id);
        }
    }

    // Create WooCommerce product
    if (class_exists('WooCommerce')) {
        $product_id = wp_insert_post(array(
            'post_title' => $title,
            'post_content' => $content,
            'post_status' => 'publish',
            'post_type' => 'product',
        ));

        if (!is_wp_error($product_id)) {
            if (class_exists('WC_Bookings')) {
                wp_set_object_terms($product_id, 'booking', 'product_type');
            }
            update_post_meta($product_id, '_regular_price', $data['price_per_day']);
            update_post_meta($product_id, '_price', $data['price_per_day']);
            update_post_meta($product_id, '_stock', $data['total_units']);
            update_post_meta($product_id, '_stock_status', 'instock');
            update_post_meta($product_id, '_manage_stock', 'yes');
            update_post_meta($vehicle_id, '_vehicle_woocommerce_product_id', $product_id);
            update_post_meta($product_id, '_linked_vehicle_id', $vehicle_id);

            $thumb_id = get_post_thumbnail_id($vehicle_id);
            if ($thumb_id) {
                set_post_thumbnail($product_id, $thumb_id);
            }
        }
    }

    $imported++;
    WP_CLI::log("✓ Imported: {$title} (Vehicle ID: {$vehicle_id})");
}

WP_CLI::success("Import complete! Imported: {$imported}, Skipped: {$skipped}");
