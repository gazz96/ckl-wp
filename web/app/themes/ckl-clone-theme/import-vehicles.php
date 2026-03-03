<?php
/**
 * Vehicle Import Script
 *
 * Import vehicles from Supabase JSON to WordPress
 * Run this file once via WP-CLI or by visiting the URL
 *
 * Usage via WP-CLI:
 * wp eval-file import-vehicles.php
 *
 * Or visit: https://yoursite.com/wp-content/themes/ckl-clone-theme/import-vehicles.php
 */

// Prevent direct access if not in WordPress context
if (!defined('ABSPATH')) {
    // If accessed directly, try to load WordPress
    $wp_load_paths = array(
        __DIR__ . '/../../../wp-load.php',
        __DIR__ . '/../../../../wp-load.php',
    );

    $loaded = false;
    foreach ($wp_load_paths as $path) {
        if (file_exists($path)) {
            require_once($path);
            $loaded = true;
            break;
        }
    }

    if (!$loaded) {
        die('Could not load WordPress. Please run via WP-CLI: wp eval-file import-vehicles.php');
    }

    // Security check
    if (!current_user_can('manage_options')) {
        die('You do not have permission to run this script.');
    }
}

// Vehicle data from Supabase
$vehicles_data = json_decode(file_get_contents(__DIR__ . '/vehicles-data.json'), true);

if (!$vehicles_data) {
    // If no file, use the inline data
    $vehicles_data = array(
        array(
            'id' => '12f78c51-b9d5-4fb3-a9c6-a3c276799af7',
            'user_id' => '8478f24c-bcd0-4fa8-9ddb-d57c007d2662',
            'name' => 'Honda',
            'model' => 'CRV',
            'price_per_day' => 300.00,
            'image_url' => 'https://htlqqgdaevwmyybaykxx.supabase.co/storage/v1/object/public/car-images/cars/1767927992928-cykytf.jpeg',
            'seats' => 5,
            'transmission' => 'automatic',
            'fuel_type' => 'petrol',
            'rating' => 0.0,
            'status' => 'available',
            'plate_number' => '-',
            'total_bookings' => 0,
            'total_units' => 1,
            'created_at' => '2026-01-09T03:06:40.930889+00:00',
            'updated_at' => '2026-02-02T08:03:25.040974+00:00',
            'image_urls' => array(
                'https://htlqqgdaevwmyybaykxx.supabase.co/storage/v1/object/public/car-images/cars/1767927992928-cykytf.jpeg'
            ),
            'car_type' => 'suv',
            'is_active' => true,
            'doors' => 4,
            'luggage' => 2,
            'has_air_condition' => true,
            'late_fee_per_hour' => 20.00,
            'grace_period_minutes' => 120
        ),
        array(
            'id' => 'a61581ab-cf38-4305-b3b3-8bc2a6ecba0c',
            'user_id' => '8478f24c-bcd0-4fa8-9ddb-d57c007d2662',
            'name' => 'Honda',
            'model' => 'ADV160',
            'price_per_day' => 80.00,
            'image_url' => 'https://htlqqgdaevwmyybaykxx.supabase.co/storage/v1/object/public/car-images/cars/1766402224544-bnlhce.png',
            'seats' => 2,
            'transmission' => 'automatic',
            'fuel_type' => 'petrol',
            'rating' => 4.5,
            'status' => 'available',
            'plate_number' => 'HON0020',
            'total_bookings' => 0,
            'total_units' => 5,
            'created_at' => '2025-12-15T03:28:51.521243+00:00',
            'updated_at' => '2026-01-08T15:42:01.790372+00:00',
            'image_urls' => array(
                'https://htlqqgdaevwmyybaykxx.supabase.co/storage/v1/object/public/car-images/cars/1766402224544-bnlhce.png'
            ),
            'car_type' => 'motorcycle',
            'is_active' => true,
            'doors' => 4,
            'luggage' => 2,
            'has_air_condition' => true,
            'late_fee_per_hour' => 10.00,
            'grace_period_minutes' => null
        ),
        array(
            'id' => '2b662aa7-8d30-495a-928d-ee9a05266926',
            'user_id' => '8478f24c-bcd0-4fa8-9ddb-d57c007d2662',
            'name' => 'Toyota',
            'model' => 'Vios',
            'price_per_day' => 110.00,
            'image_url' => 'https://htlqqgdaevwmyybaykxx.supabase.co/storage/v1/object/public/car-images/cars/1766402438169-cj60xq.png',
            'seats' => 5,
            'transmission' => 'automatic',
            'fuel_type' => 'petrol',
            'rating' => 4.5,
            'status' => 'available',
            'plate_number' => 'TOY0001',
            'total_bookings' => 0,
            'total_units' => 7,
            'created_at' => '2025-12-15T03:28:51.521243+00:00',
            'updated_at' => '2026-01-08T15:42:01.790372+00:00',
            'image_urls' => array(
                'https://htlqqgdaevwmyybaykxx.supabase.co/storage/v1/object/public/car-images/cars/1766402438169-cj60xq.png'
            ),
            'car_type' => 'sedan',
            'is_active' => true,
            'doors' => 4,
            'luggage' => 2,
            'has_air_condition' => true,
            'late_fee_per_hour' => 10.00,
            'grace_period_minutes' => null
        ),
        array(
            'id' => '05a5e661-8f87-440c-970a-e8f16f2aadf8',
            'user_id' => '8478f24c-bcd0-4fa8-9ddb-d57c007d2662',
            'name' => 'Perodua',
            'model' => 'Axia',
            'price_per_day' => 70.00,
            'image_url' => 'https://htlqqgdaevwmyybaykxx.supabase.co/storage/v1/object/public/car-images/cars/1766403192757-5tbala.png',
            'seats' => 4,
            'transmission' => 'automatic',
            'fuel_type' => 'petrol',
            'rating' => 4.5,
            'status' => 'available',
            'plate_number' => 'PER0003',
            'total_bookings' => 0,
            'total_units' => 12,
            'created_at' => '2025-12-15T03:28:51.521243+00:00',
            'updated_at' => '2026-01-08T15:42:01.790372+00:00',
            'image_urls' => array(
                'https://htlqqgdaevwmyybaykxx.supabase.co/storage/v1/object/public/car-images/cars/1766403192757-5tbala.png'
            ),
            'car_type' => 'compact',
            'is_active' => true,
            'doors' => 4,
            'luggage' => 2,
            'has_air_condition' => true,
            'late_fee_per_hour' => 10.00,
            'grace_period_minutes' => null
        ),
        array(
            'id' => '8c50259f-88be-45f2-8585-f1aa01511c92',
            'user_id' => '8478f24c-bcd0-4fa8-9ddb-d57c007d2662',
            'name' => 'Perodua',
            'model' => 'Myvi',
            'price_per_day' => 80.00,
            'image_url' => 'https://htlqqgdaevwmyybaykxx.supabase.co/storage/v1/object/public/car-images/cars/1766402324839-h2flej.png',
            'seats' => 4,
            'transmission' => 'automatic',
            'fuel_type' => 'petrol',
            'rating' => 4.5,
            'status' => 'available',
            'plate_number' => 'PER0004',
            'total_bookings' => 0,
            'total_units' => 6,
            'created_at' => '2025-12-15T03:28:51.521243+00:00',
            'updated_at' => '2026-01-08T15:42:01.790372+00:00',
            'image_urls' => array(
                'https://htlqqgdaevwmyybaykxx.supabase.co/storage/v1/object/public/car-images/cars/1766402324839-h2flej.png'
            ),
            'car_type' => 'compact',
            'is_active' => true,
            'doors' => 4,
            'luggage' => 2,
            'has_air_condition' => true,
            'late_fee_per_hour' => 10.00,
            'grace_period_minutes' => null
        ),
        array(
            'id' => '2108e2df-cd2b-426c-9607-668c5315026c',
            'user_id' => '8478f24c-bcd0-4fa8-9ddb-d57c007d2662',
            'name' => 'Nissan',
            'model' => 'Almera Turbo',
            'price_per_day' => 120.00,
            'image_url' => 'https://htlqqgdaevwmyybaykxx.supabase.co/storage/v1/object/public/car-images/cars/1766401990671-8rh3bv.png',
            'seats' => 5,
            'transmission' => 'automatic',
            'fuel_type' => 'petrol',
            'rating' => 4.5,
            'status' => 'available',
            'plate_number' => 'NIS0006',
            'total_bookings' => 0,
            'total_units' => 5,
            'created_at' => '2025-12-15T03:28:51.521243+00:00',
            'updated_at' => '2026-01-08T15:42:01.790372+00:00',
            'image_urls' => array(
                'https://htlqqgdaevwmyybaykxx.supabase.co/storage/v1/object/public/car-images/cars/1766401990671-8rh3bv.png'
            ),
            'car_type' => 'sedan',
            'is_active' => true,
            'doors' => 4,
            'luggage' => 2,
            'has_air_condition' => true,
            'late_fee_per_hour' => 10.00,
            'grace_period_minutes' => null
        ),
        array(
            'id' => '3b12a698-6e92-4381-9be9-4ce06e9574e4',
            'user_id' => '8478f24c-bcd0-4fa8-9ddb-d57c007d2662',
            'name' => 'Honda',
            'model' => 'City',
            'price_per_day' => 120.00,
            'image_url' => 'https://htlqqgdaevwmyybaykxx.supabase.co/storage/v1/object/public/car-images/cars/1766401444112-76tfyx.png',
            'seats' => 5,
            'transmission' => 'automatic',
            'fuel_type' => 'petrol',
            'rating' => 4.5,
            'status' => 'available',
            'plate_number' => 'HON0007',
            'total_bookings' => 0,
            'total_units' => 5,
            'created_at' => '2025-12-15T03:28:51.521243+00:00',
            'updated_at' => '2026-01-08T15:42:01.790372+00:00',
            'image_urls' => array(
                'https://htlqqgdaevwmyybaykxx.supabase.co/storage/v1/object/public/car-images/cars/1766401444112-76tfyx.png'
            ),
            'car_type' => 'sedan',
            'is_active' => true,
            'doors' => 4,
            'luggage' => 2,
            'has_air_condition' => true,
            'late_fee_per_hour' => 10.00,
            'grace_period_minutes' => null
        ),
        array(
            'id' => '78ae46a9-07fc-4aba-9812-6b298b03e092',
            'user_id' => '8478f24c-bcd0-4fa8-9ddb-d57c007d2662',
            'name' => 'Perodua',
            'model' => 'Alza',
            'price_per_day' => 130.00,
            'image_url' => 'https://htlqqgdaevwmyybaykxx.supabase.co/storage/v1/object/public/car-images/cars/1766401344406-cu5ias.png',
            'seats' => 7,
            'transmission' => 'automatic',
            'fuel_type' => 'petrol',
            'rating' => 4.5,
            'status' => 'available',
            'plate_number' => 'PER0002',
            'total_bookings' => 0,
            'total_units' => 5,
            'created_at' => '2025-12-15T03:28:51.521243+00:00',
            'updated_at' => '2026-01-08T15:42:01.790372+00:00',
            'image_urls' => array(
                'https://htlqqgdaevwmyybaykxx.supabase.co/storage/v1/object/public/car-images/cars/1766401344406-cu5ias.png'
            ),
            'car_type' => 'mpv',
            'is_active' => true,
            'doors' => 4,
            'luggage' => 2,
            'has_air_condition' => true,
            'late_fee_per_hour' => 15.00,
            'grace_period_minutes' => null
        ),
        array(
            'id' => '3564c24e-2b06-48ee-bc9b-33f6688f35d5',
            'user_id' => '8478f24c-bcd0-4fa8-9ddb-d57c007d2662',
            'name' => 'Toyota',
            'model' => 'Avanza',
            'price_per_day' => 120.00,
            'image_url' => 'https://htlqqgdaevwmyybaykxx.supabase.co/storage/v1/object/public/car-images/cars/1766401897840-zilpuj.png',
            'seats' => 7,
            'transmission' => 'automatic',
            'fuel_type' => 'petrol',
            'rating' => 4.5,
            'status' => 'available',
            'plate_number' => 'TOY0009',
            'total_bookings' => 0,
            'total_units' => 5,
            'created_at' => '2025-12-15T03:28:51.521243+00:00',
            'updated_at' => '2026-01-08T15:42:01.790372+00:00',
            'image_urls' => array(
                'https://htlqqgdaevwmyybaykxx.supabase.co/storage/v1/object/public/car-images/cars/1766401897840-zilpuj.png'
            ),
            'car_type' => 'mpv',
            'is_active' => true,
            'doors' => 4,
            'luggage' => 2,
            'has_air_condition' => true,
            'late_fee_per_hour' => 15.00,
            'grace_period_minutes' => null
        ),
        array(
            'id' => 'b81f0bde-4f27-498d-979e-5e2df43f7cd6',
            'user_id' => '8478f24c-bcd0-4fa8-9ddb-d57c007d2662',
            'name' => 'Toyota',
            'model' => 'Innova',
            'price_per_day' => 180.00,
            'image_url' => 'https://htlqqgdaevwmyybaykxx.supabase.co/storage/v1/object/public/car-images/cars/1766401006157-p6edp.png',
            'seats' => 8,
            'transmission' => 'automatic',
            'fuel_type' => 'petrol',
            'rating' => 4.5,
            'status' => 'available',
            'plate_number' => 'TOY0010',
            'total_bookings' => 0,
            'total_units' => 5,
            'created_at' => '2025-12-15T03:28:51.521243+00:00',
            'updated_at' => '2026-01-08T15:42:01.790372+00:00',
            'image_urls' => array(
                'https://htlqqgdaevwmyybaykxx.supabase.co/storage/v1/object/public/car-images/cars/1766401006157-p6edp.png'
            ),
            'car_type' => 'mpv',
            'is_active' => true,
            'doors' => 4,
            'luggage' => 2,
            'has_air_condition' => true,
            'late_fee_per_hour' => 15.00,
            'grace_period_minutes' => null
        ),
        array(
            'id' => '0e83986e-9c1e-4802-a72a-eeb7b3700c19',
            'user_id' => '8478f24c-bcd0-4fa8-9ddb-d57c007d2662',
            'name' => 'Nissan',
            'model' => 'Serena',
            'price_per_day' => 180.00,
            'image_url' => 'https://htlqqgdaevwmyybaykxx.supabase.co/storage/v1/object/public/car-images/cars/1766401726832-8c2vyc.png',
            'seats' => 7,
            'transmission' => 'automatic',
            'fuel_type' => 'petrol',
            'rating' => 4.5,
            'status' => 'available',
            'plate_number' => 'NIS0011',
            'total_bookings' => 0,
            'total_units' => 6,
            'created_at' => '2025-12-15T03:28:51.521243+00:00',
            'updated_at' => '2026-01-08T15:42:01.790372+00:00',
            'image_urls' => array(
                'https://htlqqgdaevwmyybaykxx.supabase.co/storage/v1/object/public/car-images/cars/1766401726832-8c2vyc.png'
            ),
            'car_type' => 'mpv',
            'is_active' => true,
            'doors' => 4,
            'luggage' => 2,
            'has_air_condition' => true,
            'late_fee_per_hour' => 15.00,
            'grace_period_minutes' => null
        ),
        array(
            'id' => 'ac9c13f6-2804-487e-970e-69ee92102b2b',
            'user_id' => '8478f24c-bcd0-4fa8-9ddb-d57c007d2662',
            'name' => 'Nissan',
            'model' => 'Urvan',
            'price_per_day' => 200.00,
            'image_url' => 'https://htlqqgdaevwmyybaykxx.supabase.co/storage/v1/object/public/car-images/cars/1766400729754-r208q.png',
            'seats' => 15,
            'transmission' => 'manual',
            'fuel_type' => 'diesel',
            'rating' => 4.5,
            'status' => 'available',
            'plate_number' => 'NIS0017',
            'total_bookings' => 0,
            'total_units' => 5,
            'created_at' => '2025-12-15T03:28:51.521243+00:00',
            'updated_at' => '2026-01-08T15:42:01.790372+00:00',
            'image_urls' => array(
                'https://htlqqgdaevwmyybaykxx.supabase.co/storage/v1/object/public/car-images/cars/1766400729754-r208q.png'
            ),
            'car_type' => 'mpv',
            'is_active' => true,
            'doors' => 4,
            'luggage' => 2,
            'has_air_condition' => true,
            'late_fee_per_hour' => 20.00,
            'grace_period_minutes' => null
        ),
        array(
            'id' => '8295273e-0d87-4af5-93ba-37f4b7602011',
            'user_id' => '8478f24c-bcd0-4fa8-9ddb-d57c007d2662',
            'name' => 'KTM',
            'model' => '1290 SuperAdventure R',
            'price_per_day' => 600.00,
            'image_url' => 'https://htlqqgdaevwmyybaykxx.supabase.co/storage/v1/object/public/car-images/cars/1766401173435-sn5etp.png',
            'seats' => 2,
            'transmission' => 'manual',
            'fuel_type' => 'petrol',
            'rating' => 4.5,
            'status' => 'available',
            'plate_number' => 'KTM0022',
            'total_bookings' => 0,
            'total_units' => 1,
            'created_at' => '2025-12-15T03:28:51.521243+00:00',
            'updated_at' => '2026-01-08T15:42:01.790372+00:00',
            'image_urls' => array(
                'https://htlqqgdaevwmyybaykxx.supabase.co/storage/v1/object/public/car-images/cars/1766401173435-sn5etp.png'
            ),
            'car_type' => 'motorcycle',
            'is_active' => true,
            'doors' => 4,
            'luggage' => 2,
            'has_air_condition' => true,
            'late_fee_per_hour' => 20.00,
            'grace_period_minutes' => null
        ),
        array(
            'id' => '85fdfb6d-0547-49ef-a303-6fb7e06ac2c9',
            'user_id' => '8478f24c-bcd0-4fa8-9ddb-d57c007d2662',
            'name' => 'Toyota',
            'model' => 'Camry',
            'price_per_day' => 350.00,
            'image_url' => 'https://htlqqgdaevwmyybaykxx.supabase.co/storage/v1/object/public/car-images/cars/1766402116232-59n22i.png',
            'seats' => 5,
            'transmission' => 'automatic',
            'fuel_type' => 'petrol',
            'rating' => 4.5,
            'status' => 'available',
            'plate_number' => 'TOY0008',
            'total_bookings' => 0,
            'total_units' => 5,
            'created_at' => '2025-12-15T03:28:51.521243+00:00',
            'updated_at' => '2026-01-08T15:42:01.790372+00:00',
            'image_urls' => array(
                'https://htlqqgdaevwmyybaykxx.supabase.co/storage/v1/object/public/car-images/cars/1766402116232-59n22i.png'
            ),
            'car_type' => 'sedan',
            'is_active' => true,
            'doors' => 4,
            'luggage' => 2,
            'has_air_condition' => true,
            'late_fee_per_hour' => 30.00,
            'grace_period_minutes' => null
        ),
        array(
            'id' => '5fe4d51e-1365-40d5-913f-45e7e126ae84',
            'user_id' => '8478f24c-bcd0-4fa8-9ddb-d57c007d2662',
            'name' => 'Hyundai',
            'model' => 'Staria',
            'price_per_day' => 350.00,
            'image_url' => 'https://htlqqgdaevwmyybaykxx.supabase.co/storage/v1/object/public/car-images/cars/1766402905006-yuzjnj.png',
            'seats' => 11,
            'transmission' => 'automatic',
            'fuel_type' => 'diesel',
            'rating' => 4.5,
            'status' => 'available',
            'plate_number' => 'HYU0013',
            'total_bookings' => 0,
            'total_units' => 4,
            'created_at' => '2025-12-15T03:28:51.521243+00:00',
            'updated_at' => '2026-01-08T15:42:01.790372+00:00',
            'image_urls' => array(
                'https://htlqqgdaevwmyybaykxx.supabase.co/storage/v1/object/public/car-images/cars/1766402905006-yuzjnj.png'
            ),
            'car_type' => 'luxury_mpv',
            'is_active' => true,
            'doors' => 4,
            'luggage' => 2,
            'has_air_condition' => true,
            'late_fee_per_hour' => 30.00,
            'grace_period_minutes' => null
        ),
        array(
            'id' => '17d514a8-d828-456b-a8a4-06f329379b50',
            'user_id' => '8478f24c-bcd0-4fa8-9ddb-d57c007d2662',
            'name' => 'Toyota',
            'model' => 'Vellfire',
            'price_per_day' => 350.00,
            'image_url' => 'https://htlqqgdaevwmyybaykxx.supabase.co/storage/v1/object/public/car-images/cars/1766403075145-t4tfpk.png',
            'seats' => 7,
            'transmission' => 'automatic',
            'fuel_type' => 'petrol',
            'rating' => 4.5,
            'status' => 'available',
            'plate_number' => 'TOY0014',
            'total_bookings' => 0,
            'total_units' => 5,
            'created_at' => '2025-12-15T03:28:51.521243+00:00',
            'updated_at' => '2026-01-08T15:42:01.790372+00:00',
            'image_urls' => array(
                'https://htlqqgdaevwmyybaykxx.supabase.co/storage/v1/object/public/car-images/cars/1766403075145-t4tfpk.png'
            ),
            'car_type' => 'luxury_mpv',
            'is_active' => true,
            'doors' => 4,
            'luggage' => 2,
            'has_air_condition' => true,
            'late_fee_per_hour' => 30.00,
            'grace_period_minutes' => null
        ),
        array(
            'id' => '8fa46c88-2e53-4691-ae20-8c5408d353a7',
            'user_id' => '8478f24c-bcd0-4fa8-9ddb-d57c007d2662',
            'name' => 'Toyota',
            'model' => 'Alphard',
            'price_per_day' => 380.00,
            'image_url' => 'https://htlqqgdaevwmyybaykxx.supabase.co/storage/v1/object/public/car-images/cars/1766402607963-t2t6fk.png',
            'seats' => 7,
            'transmission' => 'automatic',
            'fuel_type' => 'petrol',
            'rating' => 4.5,
            'status' => 'available',
            'plate_number' => 'TOY0015',
            'total_bookings' => 0,
            'total_units' => 7,
            'created_at' => '2025-12-15T03:28:51.521243+00:00',
            'updated_at' => '2026-01-08T15:42:01.790372+00:00',
            'image_urls' => array(
                'https://htlqqgdaevwmyybaykxx.supabase.co/storage/v1/object/public/car-images/cars/1766402607963-t2t6fk.png'
            ),
            'car_type' => 'luxury_mpv',
            'is_active' => true,
            'doors' => 4,
            'luggage' => 2,
            'has_air_condition' => true,
            'late_fee_per_hour' => 30.00,
            'grace_period_minutes' => null
        ),
        array(
            'id' => 'dcddf543-1f2b-48a0-8706-200e6ed1a044',
            'user_id' => '8478f24c-bcd0-4fa8-9ddb-d57c007d2662',
            'name' => 'Perodua',
            'model' => 'Bezza',
            'price_per_day' => 90.00,
            'image_url' => 'https://htlqqgdaevwmyybaykxx.supabase.co/storage/v1/object/public/car-images/cars/1766401617346-ms1rv.png',
            'seats' => 5,
            'transmission' => 'automatic',
            'fuel_type' => 'petrol',
            'rating' => 4.5,
            'status' => 'available',
            'plate_number' => 'PER0005',
            'total_bookings' => 0,
            'total_units' => 10,
            'created_at' => '2025-12-15T03:28:51.521243+00:00',
            'updated_at' => '2026-01-08T15:42:01.790372+00:00',
            'image_urls' => array(
                'https://htlqqgdaevwmyybaykxx.supabase.co/storage/v1/object/public/car-images/cars/1766401617346-ms1rv.png'
            ),
            'car_type' => 'sedan',
            'is_active' => true,
            'doors' => 4,
            'luggage' => 2,
            'has_air_condition' => true,
            'late_fee_per_hour' => 10.00,
            'grace_period_minutes' => null
        ),
        array(
            'id' => 'c5397bf2-8873-4626-9b49-1203d960eb2f',
            'user_id' => '8478f24c-bcd0-4fa8-9ddb-d57c007d2662',
            'name' => 'Mitsubishi',
            'model' => 'Triton',
            'price_per_day' => 350.00,
            'image_url' => 'https://htlqqgdaevwmyybaykxx.supabase.co/storage/v1/object/public/car-images/cars/1766401259573-kbzw6g.png',
            'seats' => 5,
            'transmission' => 'automatic',
            'fuel_type' => 'diesel',
            'rating' => 4.5,
            'status' => 'available',
            'plate_number' => 'MIT0018',
            'total_bookings' => 0,
            'total_units' => 3,
            'created_at' => '2025-12-15T03:28:51.521243+00:00',
            'updated_at' => '2026-01-08T15:42:01.790372+00:00',
            'image_urls' => array(
                'https://htlqqgdaevwmyybaykxx.supabase.co/storage/v1/object/public/car-images/cars/1766401259573-kbzw6g.png'
            ),
            'car_type' => '4x4',
            'is_active' => true,
            'doors' => 4,
            'luggage' => 2,
            'has_air_condition' => true,
            'late_fee_per_hour' => 20.00,
            'grace_period_minutes' => null
        ),
        array(
            'id' => 'dbe50e00-5516-4634-ad59-8c7d5f0473c3',
            'user_id' => '8478f24c-bcd0-4fa8-9ddb-d57c007d2662',
            'name' => 'Hyundai',
            'model' => 'Starex',
            'price_per_day' => 330.00,
            'image_url' => 'https://htlqqgdaevwmyybaykxx.supabase.co/storage/v1/object/public/car-images/cars/1766402797264-lha4hh.png',
            'seats' => 11,
            'transmission' => 'automatic',
            'fuel_type' => 'diesel',
            'rating' => 4.5,
            'status' => 'available',
            'plate_number' => 'HYU0012',
            'total_bookings' => 0,
            'total_units' => 4,
            'created_at' => '2025-12-15T03:28:51.521243+00:00',
            'updated_at' => '2026-01-08T15:42:01.790372+00:00',
            'image_urls' => array(
                'https://htlqqgdaevwmyybaykxx.supabase.co/storage/v1/object/public/car-images/cars/1766402797264-lha4hh.png'
            ),
            'car_type' => 'luxury_mpv',
            'is_active' => true,
            'doors' => 4,
            'luggage' => 2,
            'has_air_condition' => true,
            'late_fee_per_hour' => 30.00,
            'grace_period_minutes' => null
        ),
        array(
            'id' => 'bdcb3f36-b0cc-48c8-95ff-ecf1533bb523',
            'user_id' => '8478f24c-bcd0-4fa8-9ddb-d57c007d2662',
            'name' => 'Toyota',
            'model' => 'Vellfire AGH 40',
            'price_per_day' => 800.00,
            'image_url' => 'https://htlqqgdaevwmyybaykxx.supabase.co/storage/v1/object/public/car-images/cars/1767884686785-itkqep.jpeg',
            'seats' => 7,
            'transmission' => 'automatic',
            'fuel_type' => 'petrol',
            'rating' => 4.5,
            'status' => 'available',
            'plate_number' => 'TOY0016',
            'total_bookings' => 0,
            'total_units' => 3,
            'created_at' => '2025-12-15T03:28:51.521243+00:00',
            'updated_at' => '2026-01-08T15:42:01.790372+00:00',
            'image_urls' => array(
                'https://htlqqgdaevwmyybaykxx.supabase.co/storage/v1/object/public/car-images/cars/1767884686785-itkqep.jpeg'
            ),
            'car_type' => 'luxury_mpv',
            'is_active' => true,
            'doors' => 4,
            'luggage' => 2,
            'has_air_condition' => true,
            'late_fee_per_hour' => 50.00,
            'grace_period_minutes' => null
        ),
        array(
            'id' => 'a40397c3-d82c-4e7f-beb0-edc0425387f1',
            'user_id' => '8478f24c-bcd0-4fa8-9ddb-d57c007d2662',
            'name' => 'Yamaha',
            'model' => 'NVX',
            'price_per_day' => 70.00,
            'image_url' => 'https://htlqqgdaevwmyybaykxx.supabase.co/storage/v1/object/public/car-images/cars/1766402513265-f1y8gk.png',
            'seats' => 2,
            'transmission' => 'automatic',
            'fuel_type' => 'petrol',
            'rating' => 4.5,
            'status' => 'available',
            'plate_number' => 'YAM0021',
            'total_bookings' => 0,
            'total_units' => 3,
            'created_at' => '2025-12-15T03:28:51.521243+00:00',
            'updated_at' => '2025-12-22T11:21:55.58422+00:00',
            'image_urls' => array(
                'https://htlqqgdaevwmyybaykxx.supabase.co/storage/v1/object/public/car-images/cars/1766402513265-f1y8gk.png'
            ),
            'car_type' => 'motorcycle',
            'is_active' => true,
            'doors' => 4,
            'luggage' => 2,
            'has_air_condition' => true,
            'late_fee_per_hour' => null,
            'grace_period_minutes' => null
        ),
        array(
            'id' => 'c211acee-d72f-4c76-b49c-47d173ad08f2',
            'user_id' => '8478f24c-bcd0-4fa8-9ddb-d57c007d2662',
            'name' => 'Mini Cooper',
            'model' => 'Convertible',
            'price_per_day' => 600.00,
            'image_url' => 'https://htlqqgdaevwmyybaykxx.supabase.co/storage/v1/object/public/car-images/cars/1766402688625-tgvaf.png',
            'seats' => 4,
            'transmission' => 'automatic',
            'fuel_type' => 'petrol',
            'rating' => 4.5,
            'status' => 'available',
            'plate_number' => 'MIN0019',
            'total_bookings' => 0,
            'total_units' => 3,
            'created_at' => '2025-12-15T03:28:51.521243+00:00',
            'updated_at' => '2026-01-08T15:42:01.790372+00:00',
            'image_urls' => array(
                'https://htlqqgdaevwmyybaykxx.supabase.co/storage/v1/object/public/car-images/cars/1766402688625-tgvaf.png'
            ),
            'car_type' => 'compact',
            'is_active' => true,
            'doors' => 4,
            'luggage' => 2,
            'has_air_condition' => true,
            'late_fee_per_hour' => 50.00,
            'grace_period_minutes' => null
        )
    );
}

/**
 * Import vehicles from Supabase data
 */
function ckl_import_vehicles($vehicles_data) {
    $imported = 0;
    $skipped = 0;
    $errors = array();

    foreach ($vehicles_data as $vehicle_data) {
        try {
            // Check if vehicle already exists by Supabase ID
            $existing = get_posts(array(
                'post_type' => 'vehicle',
                'meta_query' => array(
                    array(
                        'key' => '_supabase_id',
                        'value' => $vehicle_data['id'],
                        'compare' => '='
                    )
                ),
                'posts_per_page' => 1
            ));

            if (!empty($existing)) {
                $skipped++;
                echo "Skipped (already exists): {$vehicle_data['name']} {$vehicle_data['model']}<br>";
                continue;
            }

            // Create vehicle post
            $post_title = $vehicle_data['name'] . ' ' . $vehicle_data['model'];
            $post_content = ckl_generate_vehicle_description($vehicle_data);

            $post_data = array(
                'post_title' => $post_title,
                'post_content' => $post_content,
                'post_status' => $vehicle_data['is_active'] ? 'publish' : 'draft',
                'post_type' => 'vehicle',
                'post_excerpt' => sprintf('Rent %s %s in Langkawi. RM%s/day. %s seats, %s transmission.',
                    $vehicle_data['name'],
                    $vehicle_data['model'],
                    number_format($vehicle_data['price_per_day'], 0),
                    $vehicle_data['seats'],
                    ucfirst($vehicle_data['transmission'])
                ),
            );

            $vehicle_id = wp_insert_post($post_data);

            if (is_wp_error($vehicle_id)) {
                throw new Exception($vehicle_id->get_error_message());
            }

            // Store Supabase ID
            update_post_meta($vehicle_id, '_supabase_id', $vehicle_data['id']);

            // Store vehicle meta
            update_post_meta($vehicle_id, '_vehicle_type', $vehicle_data['car_type']);
            update_post_meta($vehicle_id, '_vehicle_passenger_capacity', $vehicle_data['seats']);
            update_post_meta($vehicle_id, '_vehicle_doors', $vehicle_data['doors']);
            update_post_meta($vehicle_id, '_vehicle_luggage', $vehicle_data['luggage']);
            update_post_meta($vehicle_id, '_vehicle_has_air_conditioning', $vehicle_data['has_air_condition'] ? '1' : '0');
            update_post_meta($vehicle_id, '_vehicle_transmission', $vehicle_data['transmission']);
            update_post_meta($vehicle_id, '_vehicle_fuel_type', $vehicle_data['fuel_type']);
            update_post_meta($vehicle_id, '_vehicle_plate_number', $vehicle_data['plate_number']);
            update_post_meta($vehicle_id, '_vehicle_units_available', $vehicle_data['total_units']);
            update_post_meta($vehicle_id, '_vehicle_price_per_day', $vehicle_data['price_per_day']);

            // Store additional meta
            update_post_meta($vehicle_id, '_vehicle_late_fee_per_hour', $vehicle_data['late_fee_per_hour']);
            update_post_meta($vehicle_id, '_vehicle_grace_period_minutes', $vehicle_data['grace_period_minutes']);
            update_post_meta($vehicle_id, '_vehicle_rating', $vehicle_data['rating']);
            update_post_meta($vehicle_id, '_vehicle_total_bookings', $vehicle_data['total_bookings']);
            update_post_meta($vehicle_id, '_vehicle_status', $vehicle_data['status']);

            // Download and set featured image
            if (!empty($vehicle_data['image_url'])) {
                $image_id = ckl_upload_image_from_url($vehicle_data['image_url'], $post_title);

                if ($image_id && !is_wp_error($image_id)) {
                    set_post_thumbnail($vehicle_id, $image_id);
                }
            }

            // Create WooCommerce product
            $product_id = ckl_create_vehicle_product($vehicle_id, $vehicle_data);

            if ($product_id) {
                update_post_meta($vehicle_id, '_vehicle_woocommerce_product_id', $product_id);
                update_post_meta($product_id, '_linked_vehicle_id', $vehicle_id);
            }

            $imported++;
            echo "<span style='color:green;'>✓ Imported:</span> {$post_title} (Vehicle ID: {$vehicle_id}, Product ID: {$product_id})<br>";

            // Flush output buffer to show progress
            if (ob_get_level()) ob_flush();
            flush();

        } catch (Exception $e) {
            $errors[] = array(
                'vehicle' => $vehicle_data['name'] . ' ' . $vehicle_data['model'],
                'error' => $e->getMessage()
            );
            echo "<span style='color:red;'>✗ Error:</span> {$vehicle_data['name']} {$vehicle_data['model']} - {$e->getMessage()}<br>";
        }
    }

    // Display summary
    echo "<hr>";
    echo "<h2>Import Summary</h2>";
    echo "<p><strong>Imported:</strong> {$imported} vehicles</p>";
    echo "<p><strong>Skipped:</strong> {$skipped} vehicles (already exist)</p>";

    if (!empty($errors)) {
        echo "<p><strong>Errors:</strong> " . count($errors) . "</p>";
        echo "<ul>";
        foreach ($errors as $error) {
            echo "<li><strong>{$error['vehicle']}:</strong> {$error['error']}</li>";
        }
        echo "</ul>";
    }
}

/**
 * Generate vehicle description from data
 */
function ckl_generate_vehicle_description($vehicle_data) {
    $car_type = ucfirst(str_replace('_', ' ', $vehicle_data['car_type']));

    $description = "<h2>{$vehicle_data['name']} {$vehicle_data['model']} - {$car_type}</h2>\n\n";
    $description .= "<p>Experience the comfort and reliability of the {$vehicle_data['name']} {$vehicle_data['model']} during your stay in Langkawi. ";
    $description .= "Perfect for {$vehicle_data['seats']} passengers with {$vehicle_data['luggage']} luggage bags.</p>\n\n";

    $description .= "<h3>Key Features:</h3>\n";
    $description .= "<ul>\n";
    $description .= "<li><strong>Seats:</strong> {$vehicle_data['seats']} passengers</li>\n";
    $description .= "<li><strong>Transmission:</strong> " . ucfirst($vehicle_data['transmission']) . "</li>\n";
    $description .= "<li><strong>Fuel Type:</strong> " . ucfirst($vehicle_data['fuel_type']) . "</li>\n";
    $description .= "<li><strong>Doors:</strong> {$vehicle_data['doors']}</li>\n";
    $description .= "<li><strong>Air Conditioning:</strong> " . ($vehicle_data['has_air_condition'] ? 'Yes' : 'No') . "</li>\n";

    if ($vehicle_data['rating'] > 0) {
        $description .= "<li><strong>Rating:</strong> {$vehicle_data['rating']}/5</li>\n";
    }

    $description .= "</ul>\n\n";

    $description .= "<h3>Pricing:</h3>\n";
    $description .= "<p>Rent this {$car_type} for <strong>RM" . number_format($vehicle_data['price_per_day'], 0) . " per day</strong>.</p>\n\n";

    if ($vehicle_data['late_fee_per_hour']) {
        $description .= "<p><small>Late fee: RM{$vehicle_data['late_fee_per_hour']}/hour</small></p>\n";
    }

    $description .= "<p>Book now and enjoy your journey in Langkawi with confidence!</p>\n";

    return $description;
}

/**
 * Create WooCommerce product for vehicle
 */
function ckl_create_vehicle_product($vehicle_id, $vehicle_data) {
    // Check if WooCommerce is active
    if (!class_exists('WooCommerce')) {
        return false;
    }

    $post_title = $vehicle_data['name'] . ' ' . $vehicle_data['model'];

    // Create product
    $product_id = wp_insert_post(array(
        'post_title'   => $post_title,
        'post_content' => ckl_generate_vehicle_description($vehicle_data),
        'post_status'  => 'publish',
        'post_type'    => 'product',
        'post_excerpt' => sprintf('Rent %s %s in Langkawi. RM%s/day.',
            $vehicle_data['name'],
            $vehicle_data['model'],
            number_format($vehicle_data['price_per_day'], 0)
        ),
    ));

    if (is_wp_error($product_id)) {
        return false;
    }

    // Set as booking product if WC Bookings is active
    if (class_exists('WC_Bookings')) {
        wp_set_object_terms($product_id, 'booking', 'product_type');
    }

    // Set pricing
    update_post_meta($product_id, '_regular_price', $vehicle_data['price_per_day']);
    update_post_meta($product_id, '_price', $vehicle_data['price_per_day']);
    update_post_meta($product_id, '_stock_status', 'instock');
    update_post_meta($product_id, '_manage_stock', 'yes');
    update_post_meta($product_id, '_stock', $vehicle_data['total_units']);

    // Set booking config if WC Bookings is active
    if (class_exists('WC_Bookings')) {
        update_post_meta($product_id, '_wc_booking_duration', '1');
        update_post_meta($product_id, '_wc_booking_duration_unit', 'day');
        update_post_meta($product_id, '_wc_booking_min_duration', '1');
        update_post_meta($product_id, '_wc_booking_max_duration', '30');
        update_post_meta($product_id, '_wc_booking_qty', '1');
        update_post_meta($product_id, '_wc_booking_base_cost', $vehicle_data['price_per_day']);
        update_post_meta($product_id, '_wc_booking_display_cost', $vehicle_data['price_per_day']);
    }

    // Sync featured image from vehicle
    $thumbnail_id = get_post_thumbnail_id($vehicle_id);
    if ($thumbnail_id) {
        set_post_thumbnail($product_id, $thumbnail_id);
    }

    return $product_id;
}

/**
 * Upload image from URL
 */
function ckl_upload_image_from_url($image_url, $title) {
    require_once(ABSPATH . 'wp-admin/includes/file.php');
    require_once(ABSPATH . 'wp-admin/includes/media.php');
    require_once(ABSPATH . 'wp-admin/includes/image.php');

    // Download file
    $tmp = download_url($image_url);

    if (is_wp_error($tmp)) {
        return $tmp;
    }

    // Get file info
    $file_array = array(
        'name' => basename($image_url),
        'tmp_name' => $tmp
    );

    // Get file extension
    $file_ext = pathinfo($image_url, PATHINFO_EXTENSION);

    // Create sanitized filename
    $filename = sanitize_title($title) . '-' . time() . '.' . $file_ext;
    $file_array['name'] = $filename;

    // Upload to WordPress
    $id = media_handle_sideload($file_array, 0);

    // Delete temp file
    @unlink($tmp);

    return $id;
}

// Run the import
?>
<!DOCTYPE html>
<html>
<head>
    <title>Vehicle Import</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; max-width: 800px; margin: 40px auto; padding: 20px; }
        h1 { color: #2271b1; }
        .progress { background: #f0f0f0; border-radius: 4px; padding: 20px; margin: 20px 0; }
        .success { color: #00a32a; }
        .error { color: #d63638; }
    </style>
</head>
<body>
    <h1>🚗 Vehicle Import from Supabase</h1>

    <div class="progress">
        <p><strong>Starting import...</strong></p>
        <?php
        // Increase timeout
        set_time_limit(300);

        // Run import
        ckl_import_vehicles($vehicles_data);
        ?>
    </div>

    <div class="actions">
        <h2>Next Steps</h2>
        <ul>
            <li><a href="<?php echo admin_url('edit.php?post_type=vehicle'); ?>">View Vehicles →</a></li>
            <li><a href="<?php echo admin_url('edit.php?post_type=product'); ?>">View Products →</a></li>
            <li><a href="<?php echo home_url('/vehicles/'); ?>">View Frontend →</a></li>
        </ul>
    </div>

    <p style="margin-top: 40px; font-size: 12px; color: #666;">
        <strong>Note:</strong> This script can be run multiple times. Existing vehicles will be skipped based on Supabase ID.
    </p>
</body>
</html>
