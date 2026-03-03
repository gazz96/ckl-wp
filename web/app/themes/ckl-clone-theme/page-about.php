<?php
/**
 * Template Name: About Us
 *
 * About Us page template for CK Langkawi Car Rental
 */

get_header();
?>

<!-- Hero Section with Breadcrumb -->
<section class="bg-accent py-20">
    <div class="container mx-auto px-4">
        <div class="flex flex-col items-center justify-center text-white">
            <h1 class="text-5xl md:text-6xl font-bold mb-4"><?php _e('About Us', 'ckl-car-rental'); ?></h1>
            <div class="flex items-center gap-2 text-lg">
                <a class="hover:text-primary transition-colors" href="<?php echo home_url('/'); ?>"><?php _e('Home', 'ckl-car-rental'); ?></a>
                <span class="text-primary">/</span>
                <span class="text-primary"><?php _e('About Us', 'ckl-car-rental'); ?></span>
            </div>
        </div>
    </div>
</section>

<!-- About Section with Overlapping Images -->
<section class="py-20 bg-background">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">
            <!-- Left: Overlapping Circular Images -->
            <div class="relative">
                <div class="relative max-w-sm md:max-w-md lg:max-w-lg mx-auto lg:mx-0">
                    <!-- Background blur effects -->
                    <div class="absolute -top-4 -left-4 md:-top-6 md:-left-6 w-16 h-16 md:w-24 md:h-24 bg-primary/10 rounded-full blur-2xl"></div>
                    <div class="absolute -bottom-4 -right-4 md:-bottom-6 md:-right-6 w-20 h-20 md:w-32 md:h-32 bg-secondary/10 rounded-full blur-2xl"></div>

                    <!-- Main overlapping images -->
                    <div class="relative -left-10 md:left-0">
                        <!-- First circle (back) -->
                        <div class="relative z-10 w-[16rem] h-[22rem] sm:w-[20rem] sm:h-[28rem] md:w-[25rem] md:h-[35rem] mx-auto">
                            <div class="absolute inset-0 rounded-full overflow-hidden border-4 md:border-8 border-background shadow-2xl">
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/about-customer-1.jpg"
                                     alt="<?php _e('Happy customer', 'ckl-car-rental'); ?>"
                                     class="w-full h-full object-cover"
                                     onerror="this.style.display='none'; this.parentElement.style.backgroundColor='#dbeafe';">
                            </div>
                            <!-- Decorative star -->
                            <div class="absolute -top-4 -right-4 md:-top-8 md:-right-8 text-primary">
                                <svg width="50" height="50" viewBox="0 0 80 80" fill="currentColor" class="md:w-20 md:h-20">
                                    <path d="M40 0L43.09 36.91L80 40L43.09 43.09L40 80L36.91 43.09L0 40L36.91 36.91L40 0Z"></path>
                                </svg>
                            </div>
                        </div>

                        <!-- Second circle (front, overlapping) -->
                        <div class="absolute -bottom-8 -right-12 sm:-bottom-12 sm:-right-16 md:-bottom-16 md:-right-24 w-[11rem] h-[16rem] sm:w-[14rem] sm:h-[22rem] md:w-[18rem] md:h-[28rem] z-20">
                            <div class="absolute inset-0 rounded-full overflow-hidden border-4 md:border-8 border-background shadow-2xl">
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/about-customer-2.jpg"
                                     alt="<?php _e('Customer with car', 'ckl-car-rental'); ?>"
                                     class="w-full h-full object-cover"
                                     onerror="this.style.display='none'; this.parentElement.style.backgroundColor='#93c5fd';">
                            </div>
                            <!-- Animated dot -->
                            <div class="absolute top-1/4 -right-2 md:-right-3 w-4 h-4 md:w-6 md:h-6 bg-primary rounded-full border-2 md:border-4 border-background animate-pulse"></div>
                        </div>

                        <!-- Bottom decorative element -->
                        <div class="absolute bottom-4 left-4 md:bottom-8 md:left-8 text-foreground/20">
                            <svg width="24" height="24" viewBox="0 0 40 40" fill="currentColor" class="md:w-10 md:h-10">
                                <path d="M20 0L22.5 17.5L40 20L22.5 22.5L20 40L17.5 22.5L0 20L17.5 17.5L20 0Z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Content -->
            <div class="space-y-8">
                <div class="inline-flex items-center gap-2 text-primary font-semibold">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 0L14.18 9.82L24 12L14.18 14.18L12 24L9.82 14.18L0 12L9.82 9.82L12 0Z"></path>
                    </svg>
                    <span class="uppercase tracking-wide text-sm"><?php _e('About Us', 'ckl-car-rental'); ?></span>
                </div>

                <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold leading-tight">
                    <?php _e('Your trusted partner in'); ?>
                    <span class="text-gradient gradient-ocean"><?php _e('reliable car rental', 'ckl-car-rental'); ?></span>
                </h2>

                <p class="text-lg text-muted-foreground leading-relaxed">
                    <?php _e('We provide premium car rental services in Langkawi with a commitment to quality, reliability, and customer satisfaction. Experience hassle-free booking and exceptional service with every rental.', 'ckl-car-rental'); ?>
                </p>

                <div class="space-y-6">
                    <!-- Feature 1 -->
                    <div class="flex gap-4 items-start group">
                        <div class="flex-shrink-0 w-16 h-16 rounded-2xl bg-primary/10 flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-white transition-all duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-8 h-8">
                                <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"></path>
                                <path d="M14 2v4a2 2 0 0 0 2 2h4"></path>
                                <path d="m9 15 2 2 4-4"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-xl font-bold mb-2"><?php _e('Easy Booking Process', 'ckl-car-rental'); ?></h3>
                            <p class="text-muted-foreground leading-relaxed"><?php _e('We have optimized the booking process so that our clients can experience the easiest and the safest service', 'ckl-car-rental'); ?></p>
                        </div>
                    </div>

                    <!-- Feature 2 -->
                    <div class="flex gap-4 items-start group">
                        <div class="flex-shrink-0 w-16 h-16 rounded-2xl bg-primary/10 flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-white transition-all duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-8 h-8">
                                <path d="m21 8-2 2-1.5-3.7A2 2 0 0 0 15.646 5H8.4a2 2 0 0 0-1.903 1.257L5 10 3 8"></path>
                                <path d="M7 14h.01"></path>
                                <path d="M17 14h.01"></path>
                                <rect width="18" height="8" x="3" y="10" rx="2"></rect>
                                <path d="M5 18v2"></path>
                                <path d="M19 18v2"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-xl font-bold mb-2"><?php _e('Convenient Pick-Up & Return Process', 'ckl-car-rental'); ?></h3>
                            <p class="text-muted-foreground leading-relaxed"><?php _e('We have optimized the booking process so that our clients can experience the easiest and the safest service', 'ckl-car-rental'); ?></p>
                        </div>
                    </div>
                </div>

                <div class="pt-4">
                    <div class="inline-flex items-center gap-0 group cursor-pointer">
                        <a href="<?php echo home_url('/contact/'); ?>" class="inline-flex items-center justify-center gap-2 whitespace-nowrap bg-primary hover:bg-primary/90 h-11 text-white px-8 py-6 text-lg font-semibold rounded-full shadow-lg hover:shadow-xl transition-all duration-300 gradient-ocean">
                            <?php _e('Contact Us', 'ckl-car-rental'); ?>
                        </a>
                        <div class="w-12 h-12 rounded-full gradient-ocean flex items-center justify-center shadow-lg hover:shadow-xl transition-all duration-300 -ml-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6 text-white transition-transform duration-300 -rotate-45 group-hover:rotate-0">
                                <path d="M5 12h14"></path>
                                <path d="m12 5 7 7-7 7"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Vision & Mission Section -->
<section class="py-20 bg-background">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <div class="inline-flex items-center gap-2 text-primary font-semibold mb-4">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 0L14.18 9.82L24 12L14.18 14.18L12 24L9.82 14.18L0 12L9.82 9.82L12 0Z"></path>
                </svg>
                <span class="uppercase tracking-wide text-sm"><?php _e('Vision Mission', 'ckl-car-rental'); ?></span>
            </div>
            <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold leading-tight mb-6">
                <?php _e('Driving excellence and innovation'); ?><br>
                <?php _e('in car rental services', 'ckl-car-rental'); ?>
            </h2>
            <div class="flex flex-wrap justify-center gap-4 mt-8">
                <button class="inline-flex items-center justify-center gap-2 whitespace-nowrap bg-primary hover:bg-primary/90 h-10 rounded-full px-8 py-6 font-semibold transition-all gradient-ocean text-white">
                    <?php _e('Our Vision', 'ckl-car-rental'); ?>
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center mt-16">
            <!-- Vision Content -->
            <div>
                <div class="inline-flex items-center gap-2 text-primary font-semibold mb-4">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 0L14.18 9.82L24 12L14.18 14.18L12 24L9.82 14.18L0 12L9.82 9.82L12 0Z"></path>
                    </svg>
                    <span class="uppercase tracking-wide text-sm"><?php _e('Our Vision', 'ckl-car-rental'); ?></span>
                </div>
                <h3 class="text-3xl md:text-4xl font-bold leading-tight mb-6">
                    <?php _e('Your Premium Car Rental, Simplified.', 'ckl-car-rental'); ?>
                </h3>
                <p class="text-lg text-muted-foreground leading-relaxed mb-8">
                    <?php _e('We combine the latest technology with meticulously maintained vehicles to make your journey seamless and comfortable.', 'ckl-car-rental'); ?>
                </p>
                <div class="space-y-4">
                    <div class="flex gap-4 items-start">
                        <div class="flex-shrink-0 w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center text-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                <polyline points="22 4 12 14.01 9 11.01"></polyline>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold mb-1"><?php _e('To be the leading vehicle rental provider in Langkawi', 'ckl-car-rental'); ?></p>
                        </div>
                    </div>
                    <div class="flex gap-4 items-start">
                        <div class="flex-shrink-0 w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center text-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                <polyline points="22 4 12 14.01 9 11.01"></polyline>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold mb-1"><?php _e('Offering trustworthy, reliable services with a focus on customer satisfaction', 'ckl-car-rental'); ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Vision Image -->
            <div class="relative">
                <div class="rounded-[3rem] overflow-hidden shadow-2xl">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/vision-image.jpg"
                         alt="<?php _e('Our Vision', 'ckl-car-rental'); ?>"
                         class="w-full h-auto object-cover transition-opacity duration-500"
                         onerror="this.style.display='none'; this.parentElement.style.backgroundColor='#eff6ff'; this.parentElement.innerHTML='<div class=\'p-12 text-center text-gray-500\'><svg class=\'w-24 h-24 mx-auto mb-4\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'1\' d=\'M15 12a3 3 0 11-6 0 3 3 0 016 0z\'></path><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'1\' d=\'M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z\'></path></svg><p>Vision Image</p></div>';">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Why Choose Us Section -->
<section class="py-20 bg-background">
    <div class="container mx-auto px-4">
        <div class="text-center mb-20">
            <div class="inline-flex items-center gap-2 text-primary font-semibold mb-4">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 0L14.18 9.82L24 12L14.18 14.18L12 24L9.82 14.18L0 12L9.82 9.82L12 0Z"></path>
                </svg>
                <span class="uppercase tracking-wide text-sm"><?php _e('Why Choose Us', 'ckl-car-rental'); ?></span>
            </div>
            <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold max-w-4xl mx-auto">
                <?php _e('Unmatched quality and service'); ?>
                <span class="text-gradient gradient-ocean"><?php _e('for your needs', 'ckl-car-rental'); ?></span>
            </h2>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12 items-center">
            <!-- Left Feature -->
            <div class="space-y-12">
                <div class="flex gap-4 items-start">
                    <div class="flex-shrink-0 w-16 h-16 rounded-full bg-primary/10 flex items-center justify-center text-primary border-2 border-primary/20">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-8 h-8">
                            <rect width="14" height="20" x="5" y="2" rx="2" ry="2"></rect>
                            <path d="M12 18h.01"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-xl font-bold mb-2"><?php _e('Effortless Online Booking', 'ckl-car-rental'); ?></h3>
                        <p class="text-muted-foreground leading-relaxed"><?php _e('Reserve your car instantly on any device.', 'ckl-car-rental'); ?></p>
                    </div>
                </div>
            </div>

            <!-- Center Circular Image -->
            <div class="relative">
                <div class="relative mx-auto max-w-md aspect-square">
                    <div class="absolute inset-0 bg-gradient-to-br from-primary/5 to-secondary/5 rounded-full blur-3xl"></div>
                    <div class="relative w-[20rem] h-[30rem] rounded-full overflow-hidden border-8 border-background shadow-2xl mx-auto">
                        <div class="absolute inset-0">
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/why-choose-us.jpg"
                                 alt="<?php _e('Car in Langkawi', 'ckl-car-rental'); ?>"
                                 class="w-full h-full object-cover"
                                 onerror="this.style.display='none'; this.parentElement.style.backgroundColor='#bfdbfe';">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
                        </div>
                    </div>
                    <div class="absolute top-1/4 -left-2 w-8 h-8 bg-primary rounded-full border-4 border-background animate-pulse"></div>
                </div>
            </div>

            <!-- Right Feature -->
            <div class="space-y-12">
                <div class="flex gap-4 items-start">
                    <div class="flex-shrink-0 w-16 h-16 rounded-full bg-primary/10 flex items-center justify-center text-primary border-2 border-primary/20">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-8 h-8">
                            <polygon points="3 11 22 2 13 21 11 13 3 11"></polygon>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-xl font-bold mb-2"><?php _e('Seamless Travel', 'ckl-car-rental'); ?></h3>
                        <p class="text-muted-foreground leading-relaxed"><?php _e('Integrated technology for efficiency and peace of mind on the road.', 'ckl-car-rental'); ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Center Feature -->
        <div class="mt-12 max-w-md mx-auto">
            <div class="flex flex-col items-center text-center gap-4">
                <div class="w-16 h-16 rounded-full bg-primary/10 flex items-center justify-center text-primary border-2 border-primary/20">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-8 h-8">
                        <path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.4 2.9A3.7 3.7 0 0 0 2 12v4c0 .6.4 1 1 1h2"></path>
                        <circle cx="7" cy="17" r="2"></circle>
                        <path d="M9 17h6"></path>
                        <circle cx="17" cy="17" r="2"></circle>
                    </svg>
                </div>
                <div>
                    <h3 class="text-xl font-bold mb-2"><?php _e('Premium Vehicles, Guaranteed', 'ckl-car-rental'); ?></h3>
                    <p class="text-muted-foreground leading-relaxed"><?php _e('Drive a clean, safe, and well-maintained vehicle every time.', 'ckl-car-rental'); ?></p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Reviews Section -->
<section class="py-20 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-primary"><?php _e('Our Reviews', 'ckl-car-rental'); ?></h2>
        </div>

        <?php
        // Fetch recent reviews
        $review_args = array(
            'post_type' => 'review',
            'posts_per_page' => 3,
            'post_status' => 'publish',
        );
        $reviews = new WP_Query($review_args);

        if ($reviews->have_posts()) :
        ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8 max-w-6xl mx-auto">
            <?php
            while ($reviews->have_posts()) : $reviews->the_post();
                $vehicle_id = get_post_meta(get_the_ID(), 'vehicle_id', true);
                $rating = get_post_meta(get_the_ID(), 'rating', true);
                $customer_name = get_post_meta(get_the_ID(), 'customer_name', true);
                $customer_country = get_post_meta(get_the_ID(), 'customer_country', true);
            ?>
            <div class="rounded-lg border bg-card text-card-foreground shadow-sm overflow-hidden hover:shadow-lg transition-all duration-300">
                <?php if ($vehicle_id) : ?>
                    <?php $vehicle_image = get_the_post_thumbnail_url($vehicle_id, 'medium'); ?>
                    <div class="relative bg-white p-6">
                        <?php if ($vehicle_image) : ?>
                            <img src="<?php echo esc_url($vehicle_image); ?>"
                                 alt="<?php echo esc_attr(get_the_title($vehicle_id)); ?>"
                                 class="w-full h-40 object-contain">
                        <?php else : ?>
                            <div class="w-full h-40 bg-gray-100 flex items-center justify-center">
                                <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <div class="p-6 border-t">
                    <p class="text-sm text-foreground/80 mb-4 italic">
                        "<?php echo wp_trim_words(get_the_content(), 20); ?>"
                    </p>

                    <?php if ($customer_name) : ?>
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-sm font-medium"><?php echo esc_html($customer_name); ?></span>
                            <?php if ($customer_country) : ?>
                                <span class="text-lg"><?php echo esc_html($customer_country); ?></span>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <p class="text-xs text-muted-foreground mb-4">
                        <?php printf(__('%s ago', 'ckl-car-rental'), human_time_diff(get_the_time('U'), current_time('timestamp'))); ?>
                    </p>

                    <?php if ($rating) : ?>
                        <div class="flex gap-1 mb-4">
                            <?php for ($i = 1; $i <= 5; $i++) : ?>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 <?php echo $i <= $rating ? 'fill-secondary text-secondary' : 'text-gray-300'; ?>">
                                    <path d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z"></path>
                                </svg>
                            <?php endfor; ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($vehicle_id) : ?>
                        <p class="text-sm font-semibold text-center"><?php echo esc_html(get_the_title($vehicle_id)); ?></p>
                    <?php endif; ?>
                </div>
            </div>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>

        <div class="flex justify-center">
            <a href="<?php echo home_url('/reviews/'); ?>" class="inline-flex items-center justify-center gap-2 whitespace-nowrap text-sm font-medium border bg-background h-10 border-primary text-primary hover:bg-primary hover:text-primary-foreground transition-all px-8 py-2 rounded-md">
                <?php _e('See more reviews', 'ckl-car-rental'); ?>
            </a>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php get_footer(); ?>
