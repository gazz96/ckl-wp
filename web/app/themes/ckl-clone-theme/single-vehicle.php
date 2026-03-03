<?php
/**
 * Single Vehicle Template
 *
 * @package CKL_Car_Rental
 */

get_header();

while (have_posts()) : the_post();
    $vehicle_id = get_the_ID();
    $vehicle_meta = ckl_get_vehicle_meta($vehicle_id);
    $special_pricing = ckl_get_vehicle_special_pricing($vehicle_id);
    $amenities = ckl_get_vehicle_amenities($vehicle_id);
    $availability = ckl_get_vehicle_availability($vehicle_id);
?>

<main class="min-h-screen bg-gray-50">
    <!-- Back Button & Breadcrumb -->
    <div class="container mx-auto px-4 pt-6 pb-4">
        <a href="<?php echo esc_url(home_url('/vehicles')); ?>"
           class="inline-flex items-center gap-2 text-gray-600 hover:text-primary transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Vehicles
        </a>
    </div>

    <div class="container mx-auto px-4 pb-6">
        <!-- Breadcrumbs -->
        <nav class="flex items-center gap-2 text-sm mb-4 text-gray-600">
            <a href="<?php echo esc_url(home_url('/')); ?>" class="hover:text-primary">Home</a>
            <span>/</span>
            <a href="<?php echo esc_url(home_url('/vehicles')); ?>" class="hover:text-primary">Browse Cars</a>
            <span>/</span>
            <span class="font-medium text-gray-900"><?php echo esc_html(get_the_title()); ?></span>
        </nav>

        <h1 class="text-4xl md:text-6xl font-bold text-primary mb-3">
            <?php echo esc_html(get_the_title()); ?>
        </h1>

        <div class="flex items-center gap-3 mb-2">
            <div class="bg-primary/10 border border-primary/20 rounded-full px-4 py-1.5">
                <span class="text-sm font-bold text-primary">
                    RM <?php echo number_format($vehicle_meta['price_per_day'], 0); ?>/day
                </span>
            </div>
        </div>

        <!-- Quick Specs Icons -->
        <div class="flex items-center gap-4 text-sm text-gray-600">
            <div class="flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <span><?php echo esc_html($vehicle_meta['passenger_capacity']); ?> seats</span>
            </div>
            <div class="flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span><?php echo esc_html(ucfirst($vehicle_meta['transmission'])); ?></span>
            </div>
            <div class="flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span><?php echo esc_html($vehicle_meta['fuel_type']); ?></span>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 pb-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- Left Column (2/3) -->
            <div class="lg:col-span-2 space-y-8">

                <!-- Gallery -->
                <?php get_template_part('template-parts/vehicle/vehicle-gallery', null, array(
                    'vehicle_id' => $vehicle_id
                )); ?>

                <!-- Specs Grid -->
                <?php get_template_part('template-parts/vehicle/vehicle-specs', null, array(
                    'meta' => $vehicle_meta
                )); ?>

                <!-- Description -->
                <div class="rounded-lg border bg-white shadow-sm p-6">
                    <h2 class="text-2xl font-bold mb-4">Overview</h2>
                    <div class="prose max-w-none text-gray-700">
                        <?php the_content(); ?>
                    </div>
                </div>

                <!-- Special Pricing -->
                <?php if (!empty($special_pricing)) : ?>
                    <?php get_template_part('template-parts/vehicle/vehicle-pricing', null, array(
                        'special_pricing' => $special_pricing,
                        'base_price' => $vehicle_meta['price_per_day']
                    )); ?>
                <?php endif; ?>

                <!-- Amenities -->
                <?php get_template_part('template-parts/vehicle/vehicle-amenities', null, array(
                    'amenities' => $amenities
                )); ?>

                <!-- Availability Calendar -->
                <?php get_template_part('template-parts/vehicle/vehicle-availability', null, array(
                    'vehicle_id' => $vehicle_id,
                    'availability' => $availability
                )); ?>

                <!-- Reviews -->
                <?php get_template_part('template-parts/vehicle/vehicle-reviews'); ?>

            </div>

            <!-- Right Column (1/3) - Booking Form -->
            <aside class="hidden lg:block">
                <div class="sticky top-24">
                    <?php get_template_part('template-parts/vehicle/vehicle-booking-form', null, array(
                        'vehicle_id' => $vehicle_id,
                        'product_id' => $vehicle_meta['woocommerce_product_id'],
                        'meta' => $vehicle_meta
                    )); ?>
                </div>
            </aside>

            <!-- Mobile Booking Form (shown below content) -->
            <div class="lg:hidden">
                <?php get_template_part('template-parts/vehicle/vehicle-booking-form', null, array(
                    'vehicle_id' => $vehicle_id,
                    'product_id' => $vehicle_meta['woocommerce_product_id'],
                    'meta' => $vehicle_meta
                )); ?>
            </div>

        </div>
    </div>
</main>

<?php endwhile;
get_footer();
