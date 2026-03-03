<?php
/**
 * How It Works Section
 *
 * Displays the 3-step process (Select, Book, Collect)
 */

$homepage_sections = get_option('ckl_homepage_sections', ckl_get_default_homepage_sections());

// Skip if disabled
if (!isset($homepage_sections['how_it_works']['enabled']) || !$homepage_sections['how_it_works']['enabled']) {
    return;
}

$steps = array(
    array(
        'title' => __('Select', 'ckl-car-rental'),
        'description' => __('Select your Langkawi ride', 'ckl-car-rental'),
        'icon' => 'mouse-pointer-click',
    ),
    array(
        'title' => __('Book', 'ckl-car-rental'),
        'description' => __('Confirm your dates and book', 'ckl-car-rental'),
        'icon' => 'calendar-check',
    ),
    array(
        'title' => __('Collect', 'ckl-car-rental'),
        'description' => __('Pick up your vehicle and explore', 'ckl-car-rental'),
        'icon' => 'car',
    ),
);
?>

<section class="py-16 bg-gradient-to-b from-white to-gray-50">
    <div class="container mx-auto px-4">
        <h2 class="text-2xl md:text-3xl font-bold text-center mb-12 text-primary">Rent your Langkawi Ride</h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto">
            <?php foreach ($steps as $step) : ?>
                <div class="rounded-lg border text-card-foreground shadow-sm flex flex-col items-center text-center p-8 hover:shadow-lg transition-shadow bg-white">
                    <div class="bg-primary w-16 h-16 rounded-lg flex items-center justify-center mb-4">
                        <?php
                        // SVG Icons based on icon type
                        $svg_icons = array(
                            'mouse-pointer-click' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-mouse-pointer-click h-8 w-8 text-white" aria-hidden="true"><path d="M14 4.1 12 6"></path><path d="m5.1 8-2.9-.8"></path><path d="m6 12-1.9 2"></path><path d="M7.2 2.2 8 5.1"></path><path d="M9.037 9.69a.498.498 0 0 1 .653-.653l11 4.5a.5.5 0 0 1-.074.949l-4.349 1.041a1 1 0 0 0-.74.739l-1.04 4.35a.5.5 0 0 1-.95.074z"></path></svg>',
                            'calendar-check' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar-check h-8 w-8 text-white" aria-hidden="true"><path d="M8 2v4"></path><path d="M16 2v4"></path><rect width="18" height="18" x="3" y="4" rx="2"></rect><path d="M3 10h18"></path><path d="m9 16 2 2 4-4"></path></svg>',
                            'car' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-car h-8 w-8 text-white" aria-hidden="true"><path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.4 2.9A3.7 3.7 0 0 0 2 12v4c0 .6.4 1 1 1h2"></path><circle cx="7" cy="17" r="2"></circle><path d="M9 17h6"></path><circle cx="17" cy="17" r="2"></circle></svg>',
                        );

                        echo isset($svg_icons[$step['icon']]) ? $svg_icons[$step['icon']] : '';
                        ?>
                    </div>
                    <h3 class="text-lg font-bold mb-3 text-accent"><?php echo esc_html($step['title']); ?></h3>
                    <p class="text-sm text-foreground/70 leading-relaxed"><?php echo esc_html($step['description']); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
