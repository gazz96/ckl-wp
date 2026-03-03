<?php
/**
 * Template Name: How To Book
 * Step-by-step guide on how to book a vehicle
 *
 * @package CKL_Car_Rental
 */

get_header();
?>

<main class="pt-20">
    <!-- Hero Section -->
    <section class="bg-accent py-20 text-white">
        <div class="container mx-auto px-4">
            <div class="max-w-3xl mx-auto text-center">
                <div class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 hover:bg-primary/80 mb-4 bg-white/20 text-white border-white/30">
                    <?php _e('Simple & Secure Process', 'ckl-car-rental'); ?>
                </div>
                <h1 class="text-5xl md:text-6xl font-bold mb-6">
                    <?php _e('How To Book', 'ckl-car-rental'); ?>
                </h1>
                <p class="text-xl text-white/90 mb-8">
                    <?php _e('Rent a car in Langkawi in 4 easy steps. From browsing to driving, we\'ve made it simple, secure, and seamless.', 'ckl-car-rental'); ?>
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="<?php echo esc_url(home_url('/auth/')); ?>">
                        <button class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none [&_svg]:size-4 [&_svg]:shrink-0 bg-secondary text-secondary-foreground hover:bg-secondary/80 h-11 rounded-md px-8 gap-2">
                            <?php _e('Sign Up', 'ckl-car-rental'); ?>
                        </button>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- 4-Step Process Section -->
    <section class="py-20 bg-gradient-to-b from-white to-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold mb-4">
                    <?php _e('Simple 4-Step', 'ckl-car-rental'); ?> <span class="text-primary"><?php _e('Process', 'ckl-car-rental'); ?></span>
                </h2>
                <p class="text-muted-foreground text-lg">
                    <?php _e('From browsing to driving in minutes', 'ckl-car-rental'); ?>
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 max-w-6xl mx-auto">
                <!-- Step 1 -->
                <div class="relative group">
                    <div class="hidden lg:block absolute top-12 left-[60%] w-full h-0.5 bg-gradient-to-r from-primary/30 to-primary/10 z-0"></div>
                    <div class="rounded-lg border text-card-foreground relative border-none shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-2 bg-white z-10">
                        <div class="p-8 text-center">
                            <div class="absolute -top-4 left-1/2 -translate-x-1/2">
                                <div class="gradient-ocean w-8 h-8 rounded-full flex items-center justify-center text-white text-sm font-bold shadow-md">
                                    1
                                </div>
                            </div>
                            <div class="gradient-ocean w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg group-hover:scale-110 transition-transform duration-300">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-10 h-10 text-white">
                                    <path d="m21 21-4.34-4.34"></path>
                                    <circle cx="11" cy="11" r="8"></circle>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold mb-3">
                                <?php _e('Search & Select', 'ckl-car-rental'); ?>
                            </h3>
                            <p class="text-muted-foreground text-sm leading-relaxed">
                                <?php _e('Choose your desired pick-up and drop-off dates, times, and locations, then search for available vehicles.', 'ckl-car-rental'); ?>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="relative group">
                    <div class="hidden lg:block absolute top-12 left-[60%] w-full h-0.5 bg-gradient-to-r from-primary/30 to-primary/10 z-0"></div>
                    <div class="rounded-lg border text-card-foreground relative border-none shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-2 bg-white z-10">
                        <div class="p-8 text-center">
                            <div class="absolute -top-4 left-1/2 -translate-x-1/2">
                                <div class="gradient-tropical w-8 h-8 rounded-full flex items-center justify-center text-white text-sm font-bold shadow-md">
                                    2
                                </div>
                            </div>
                            <div class="gradient-tropical w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg group-hover:scale-110 transition-transform duration-300">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-10 h-10 text-white">
                                    <path d="M8 2v4"></path>
                                    <path d="M16 2v4"></path>
                                    <rect width="18" height="18" x="3" y="4" rx="2"></rect>
                                    <path d="M3 10h18"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold mb-3">
                                <?php _e('Book Your Vehicle', 'ckl-car-rental'); ?>
                            </h3>
                            <p class="text-muted-foreground text-sm leading-relaxed">
                                <?php _e('Browse the catalogue of available vehicles and select the one that suits your needs.', 'ckl-car-rental'); ?>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="relative group">
                    <div class="hidden lg:block absolute top-12 left-[60%] w-full h-0.5 bg-gradient-to-r from-primary/30 to-primary/10 z-0"></div>
                    <div class="rounded-lg border text-card-foreground relative border-none shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-2 bg-white z-10">
                        <div class="p-8 text-center">
                            <div class="absolute -top-4 left-1/2 -translate-x-1/2">
                                <div class="gradient-sunset w-8 h-8 rounded-full flex items-center justify-center text-white text-sm font-bold shadow-md">
                                    3
                                </div>
                            </div>
                            <div class="gradient-sunset w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg group-hover:scale-110 transition-transform duration-300">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-10 h-10 text-white">
                                    <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"></path>
                                    <path d="M14 2v4a2 2 0 0 0 2 2h4"></path>
                                    <path d="M10 9H8"></path>
                                    <path d="M16 13H8"></path>
                                    <path d="M16 17H8"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold mb-3">
                                <?php _e('Enter Details', 'ckl-car-rental'); ?>
                            </h3>
                            <p class="text-muted-foreground text-sm leading-relaxed">
                                <?php _e('Fill in your driver\'s details and any necessary booking information (e.g., flight number).', 'ckl-car-rental'); ?>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Step 4 -->
                <div class="relative group">
                    <div class="rounded-lg border text-card-foreground relative border-none shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-2 bg-white z-10">
                        <div class="p-8 text-center">
                            <div class="absolute -top-4 left-1/2 -translate-x-1/2">
                                <div class="gradient-ocean w-8 h-8 rounded-full flex items-center justify-center text-white text-sm font-bold shadow-md">
                                    4
                                </div>
                            </div>
                            <div class="gradient-ocean w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg group-hover:scale-110 transition-transform duration-300">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-10 h-10 text-white">
                                    <rect width="20" height="14" x="2" y="5" rx="2"></rect>
                                    <line x1="2" x2="22" y1="10" y2="10"></line>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold mb-3">
                                <?php _e('Pay & Confirm', 'ckl-car-rental'); ?>
                            </h3>
                            <p class="text-muted-foreground text-sm leading-relaxed">
                                <?php _e('Complete the advance payment to confirm and secure your rental order.', 'ckl-car-rental'); ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center mt-12">
                <a href="<?php echo esc_url(home_url('/vehicles/')); ?>">
                    <button class="inline-flex items-center justify-center gap-2 whitespace-nowrap text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none [&_svg]:size-4 [&_svg]:shrink-0 bg-primary hover:bg-primary/90 h-11 rounded-md gradient-ocean text-white px-8">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 mr-2">
                            <path d="m21 21-4.34-4.34"></path>
                            <circle cx="11" cy="11" r="8"></circle>
                        </svg>
                        <?php _e('Start Browsing Vehicles', 'ckl-car-rental'); ?>
                    </button>
                </a>
            </div>
        </div>
    </section>

    <!-- What You'll Need Section -->
    <section class="py-20 bg-muted/30">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold mb-4">
                    <?php _e('What You\'ll', 'ckl-car-rental'); ?> <span class="text-[#FBB832]"><?php _e('Need', 'ckl-car-rental'); ?></span>
                </h2>
                <p class="text-muted-foreground text-lg max-w-2xl mx-auto">
                    <?php _e('Make sure you have these ready before booking', 'ckl-car-rental'); ?>
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Card 1 -->
                <div class="rounded-lg border bg-card text-card-foreground border-none shadow-lg text-center">
                    <div class="p-6">
                        <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-8 h-8 text-primary">
                                <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"></path>
                                <path d="M14 2v4a2 2 0 0 0 2 2h4"></path>
                                <path d="M10 9H8"></path>
                                <path d="M16 13H8"></path>
                                <path d="M16 17H8"></path>
                            </svg>
                        </div>
                        <h3 class="font-bold text-lg mb-2">
                            <?php _e('Valid Driver\'s License', 'ckl-car-rental'); ?>
                        </h3>
                        <p class="text-sm text-muted-foreground">
                            <?php _e('At least 1 year old, with International Driving Permit for international visitors', 'ckl-car-rental'); ?>
                        </p>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="rounded-lg border bg-card text-card-foreground border-none shadow-lg text-center">
                    <div class="p-6">
                        <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-8 h-8 text-primary">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                                <path d="M16 3.128a4 4 0 0 1 0 7.744"></path>
                                <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                            </svg>
                        </div>
                        <h3 class="font-bold text-lg mb-2">
                            <?php _e('Minimum Age 21', 'ckl-car-rental'); ?>
                        </h3>
                        <p class="text-sm text-muted-foreground">
                            <?php _e('Must be 21+ years old to rent a vehicle', 'ckl-car-rental'); ?>
                        </p>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="rounded-lg border bg-card text-card-foreground border-none shadow-lg text-center">
                    <div class="p-6">
                        <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-8 h-8 text-primary">
                                <rect width="20" height="14" x="2" y="5" rx="2"></rect>
                                <line x1="2" x2="22" y1="10" y2="10"></line>
                            </svg>
                        </div>
                        <h3 class="font-bold text-lg mb-2">
                            <?php _e('Payment Method', 'ckl-car-rental'); ?>
                        </h3>
                        <p class="text-sm text-muted-foreground">
                            <?php _e('Credit/debit card for booking and security deposit', 'ckl-car-rental'); ?>
                        </p>
                    </div>
                </div>

                <!-- Card 4 -->
                <div class="rounded-lg border bg-card text-card-foreground border-none shadow-lg text-center">
                    <div class="p-6">
                        <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-8 h-8 text-primary">
                                <path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"></path>
                            </svg>
                        </div>
                        <h3 class="font-bold text-lg mb-2">
                            <?php _e('Identification', 'ckl-car-rental'); ?>
                        </h3>
                        <p class="text-sm text-muted-foreground">
                            <?php _e('Malaysian IC or valid passport for verification', 'ckl-car-rental'); ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Final CTA Section -->
    <section class="py-20 bg-accent text-white">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-4xl font-bold mb-4">
                <?php _e('Ready to Start Your Langkawi Adventure?', 'ckl-car-rental'); ?>
            </h2>
            <p class="text-xl text-white/90 mb-8 max-w-2xl mx-auto">
                <?php _e('Join thousands of travelers who explored Langkawi with CK Langkawi', 'ckl-car-rental'); ?>
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="<?php echo esc_url(home_url('/auth/')); ?>">
                    <button class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none [&_svg]:size-4 [&_svg]:shrink-0 bg-secondary text-secondary-foreground hover:bg-secondary/80 h-11 rounded-md px-8 gap-2">
                        <?php _e('Sign Up To Book a Vehicle', 'ckl-car-rental'); ?>
                    </button>
                </a>
            </div>
        </div>
    </section>
</main>

<!-- Custom CSS for Gradients -->
<style>
.gradient-ocean {
    background: linear-gradient(135deg, #06b6d4 0%, #3b82f6 100%);
}

.gradient-tropical {
    background: linear-gradient(135deg, #f59e0b 0%, #ef4444 100%);
}

.gradient-sunset {
    background: linear-gradient(135deg, #f97316 0%, #ec4899 100%);
}
</style>

<?php get_footer(); ?>
