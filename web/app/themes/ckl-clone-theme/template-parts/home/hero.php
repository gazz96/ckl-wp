<?php
/**
 * Hero Section
 *
 * Displays the main hero section with background images, title, subtitle, and search form
 */

$hero_settings = get_option('ckl_hero_settings', ckl_get_default_hero_settings());
$homepage_sections = get_option('ckl_homepage_sections', ckl_get_default_homepage_sections());

// Skip if disabled
if (!isset($homepage_sections['hero']['enabled']) || !$homepage_sections['hero']['enabled']) {
    return;
}

// Default background images (from provided HTML)
$default_background_images = array(
    'https://storage.baharihari.com/bahari/ck-langkawi/eirik-skarstein-6yotiQwW0Gs-unsplash.jpg',
    'https://storage.baharihari.com/bahari/ck-langkawi/jay-tun-0dF2fJjTHCw-unsplash.jpg',
    'https://images.unsplash.com/photo-1519046904884-53103b34b206?w=1920'
);

// Use admin settings if configured, otherwise use defaults
$background_images = !empty($hero_settings['background_images']) ? $hero_settings['background_images'] : $default_background_images;
$has_slideshow = !empty($background_images);
?>

<section class="hero-section relative min-h-[70vh] md:min-h-[500px] bg-gradient-to-r from-blue-600 to-blue-800 text-white overflow-hidden">
    <!-- Background Slideshow -->
    <?php if ($has_slideshow) : ?>
        <div class="hero-slideshow absolute inset-0">
            <?php foreach ($background_images as $i => $image) : ?>
                <img src="<?php echo esc_url($image); ?>"
                     alt=""
                     class="hero-slide absolute inset-0 w-full h-full object-cover transition-opacity duration-1000 <?php echo $i === 0 ? 'opacity-100' : 'opacity-0'; ?>"
                     data-slide="<?php echo $i; ?>">
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- Gradient Overlay -->
    <div class="absolute inset-0 bg-gradient-to-br from-accent/70 via-accent/60 to-accent/50"></div>

    <div class="container mx-auto px-4 relative z-10">
        <div class="max-w-4xl mx-auto text-center">
            <!-- Hero Title -->
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-6 animate-fade-in">
                <?php echo esc_html($hero_settings['title']); ?>
            </h1>

            <!-- Hero Subtitle -->
            <p class="text-lg md:text-xl mb-8 opacity-90 animate-fade-in">
                <?php echo esc_html($hero_settings['subtitle']); ?>
            </p>

            <!-- Search Form -->
            <?php if (isset($hero_settings['show_search_form']) && $hero_settings['show_search_form']) : ?>
                <div class="mt-8 md:mt-12 hidden md:block">
                    <div class="bg-white rounded-xl shadow-2xl max-w-5xl mx-auto overflow-hidden text-gray-800 animate-slide-up">
                        <!-- Category Tabs -->
                        <div class="category-tabs-container grid grid-cols-5 border-b">
                            <button type="button"
                                    class="hero-category-tab relative flex flex-col items-center justify-center py-4 px-2 transition-all border-b-2 border-secondary bg-white text-foreground cursor-pointer"
                                    data-category="cars"
                                    role="tab">
                                <svg class="h-5 w-5 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.4 2.9A3.7 3.7 0 0 0 2 12v4c0 .6.4 1 1 1h2"/>
                                    <circle cx="7" cy="17" r="2"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17h6"/>
                                    <circle cx="17" cy="17" r="2"/>
                                </svg>
                                <span class="text-xs font-medium">Car Rental</span>
                            </button>

                            <button type="button"
                                    class="hero-category-tab relative flex flex-col items-center justify-center py-4 px-2 transition-all bg-gray-50 text-foreground/60 hover:bg-gray-100 cursor-pointer"
                                    data-category="motorcycles"
                                    role="tab">
                                <svg class="h-5 w-5 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <circle cx="18.5" cy="17.5" r="2.5" stroke-width="2"/>
                                    <circle cx="5.5" cy="17.5" r="2.5" stroke-width="2"/>
                                    <circle cx="15" cy="5" r="1"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 17.5V14l-3-3 4-3 2 3h2"/>
                                </svg>
                                <span class="text-xs font-medium">Bike</span>
                            </button>
                        </div>

                        <form id="hero-search-form" class="p-6">
                            <!-- Hidden category input -->
                            <input type="hidden" name="category" id="hero-category" value="cars">

                            <div class="flex flex-col lg:flex-row gap-3 items-end">
                                <!-- Pickup Location -->
                                <div class="flex-1 min-w-[200px]">
                                    <label class="block text-sm font-medium mb-2" for="pickup-location">
                                        <?php _e('Pick-up Location', 'ckl-car-rental'); ?>
                                    </label>
                                    <button type="button"
                                            id="location-selector-btn"
                                            class="location-selector w-full px-4 py-2 border rounded-md h-12 text-sm border-border/50 focus:border-secondary focus:ring-2 focus:ring-secondary/20 flex items-center gap-2 bg-white text-left"
                                            role="combobox"
                                            aria-haspopup="listbox"
                                            aria-expanded="false">
                                        <svg class="h-4 w-4 text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 0 1-2.827 0l-4.244-4.243a8 8 0 1 1 11.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
                                        </svg>
                                        <span id="selected-location" class="truncate flex-1"><?php _e('Select location', 'ckl-car-rental'); ?></span>
                                        <svg class="h-4 w-4 text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"/>
                                        </svg>
                                    </button>
                                    <select name="pickup_location" id="pickup-location" class="sr-only">
                                        <option value=""><?php _e('Select location', 'ckl-car-rental'); ?></option>
                                        <?php if (class_exists('CKL_Hero_Search_Locations')) : ?>
                                            <?php
                                            $all_locations = CKL_Hero_Search_Locations::get_all_locations();
                                            if (isset($all_locations['free'])) :
                                                foreach ($all_locations['free'] as $slug => $loc) :
                                            ?>
                                                <option value="<?php echo esc_attr($slug); ?>">
                                                    <?php echo esc_html($loc['name']); ?>
                                                </option>
                                            <?php
                                                endforeach;
                                            endif;
                                            if (isset($all_locations['custom'])) :
                                                foreach ($all_locations['custom'] as $slug => $loc) :
                                            ?>
                                                <option value="<?php echo esc_attr($slug); ?>">
                                                    <?php echo esc_html($loc['name']); ?> (+RM<?php echo $loc['fee']; ?>)
                                                </option>
                                            <?php
                                                endforeach;
                                            endif;
                                            ?>
                                        <?php endif; ?>
                                    </select>
                                </div>

                                <!-- Pickup Date -->
                                <div class="flex-1 min-w-[140px]">
                                    <label class="block text-sm font-medium mb-2" for="pickup-date">
                                        <?php _e('Pick-up Date', 'ckl-car-rental'); ?>
                                    </label>
                                    <input type="date"
                                           name="pickup_date"
                                           id="pickup-date"
                                           class="w-full px-3 py-2 border rounded-md h-12 text-sm border-border/50 focus:border-secondary">
                                </div>

                                <!-- Pickup Time -->
                                <div class="flex-1 min-w-[100px]">
                                    <label class="block text-sm font-medium mb-2" for="pickup-time">
                                        <?php _e('Time', 'ckl-car-rental'); ?>
                                    </label>
                                    <input type="time"
                                           name="pickup_time"
                                           id="pickup-time"
                                           value="06:00"
                                           class="w-full px-3 py-2 border rounded-md h-12 text-sm border-border/50 focus:border-secondary">
                                </div>

                                <!-- Return Date -->
                                <div class="flex-1 min-w-[140px]">
                                    <label class="block text-sm font-medium mb-2" for="return-date">
                                        <?php _e('Return Date', 'ckl-car-rental'); ?>
                                    </label>
                                    <input type="date"
                                           name="return_date"
                                           id="return-date"
                                           class="w-full px-3 py-2 border rounded-md h-12 text-sm border-border/50 focus:border-secondary">
                                </div>

                                <!-- Return Time -->
                                <div class="flex-1 min-w-[100px]">
                                    <label class="block text-sm font-medium mb-2" for="return-time">
                                        <?php _e('Time', 'ckl-car-rental'); ?>
                                    </label>
                                    <input type="time"
                                           name="return_time"
                                           id="return-time"
                                           value="06:00"
                                           class="w-full px-3 py-2 border rounded-md h-12 text-sm border-border/50 focus:border-secondary">
                                </div>

                                <!-- Search Button -->
                                <div class="flex-shrink-0">
                                    <button type="submit"
                                            class="inline-flex items-center justify-center gap-2 bg-secondary hover:bg-secondary/90 text-white h-12 px-6 text-sm font-semibold rounded-md transition-all">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-4.34-4.34"/>
                                            <circle cx="11" cy="11" r="8"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Return to Different Location -->
                            <div class="mt-4 flex items-center gap-2">
                                <input type="checkbox"
                                       id="return-location"
                                       class="w-4 h-4 rounded border-gray-300 accent-secondary">
                                <label for="return-location" class="text-sm text-foreground/70">
                                    <?php _e('Return to another location', 'ckl-car-rental'); ?>
                                </label>
                            </div>
                        </form>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Scroll Indicator -->
    <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
        <svg class="w-6 h-6 text-white opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
        </svg>
    </div>
</section>

<?php if ($has_slideshow && count($background_images) > 1) : ?>
    <script>
    // Background slideshow with img tags
    (function() {
        const slides = document.querySelectorAll('.hero-slide');
        let currentSlide = 0;
        const interval = 5000; // 5 seconds

        function nextSlide() {
            slides[currentSlide].classList.remove('opacity-100');
            slides[currentSlide].classList.add('opacity-0');
            currentSlide = (currentSlide + 1) % slides.length;
            slides[currentSlide].classList.remove('opacity-0');
            slides[currentSlide].classList.add('opacity-100');
        }

        setInterval(nextSlide, interval);
    })();
    </script>
<?php endif; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Hero category tabs
    const heroTabs = document.querySelectorAll('.hero-category-tab');
    const categoryInput = document.getElementById('hero-category');

    heroTabs.forEach(function(tab) {
        tab.addEventListener('click', function() {
            const category = this.getAttribute('data-category');

            // Update active tab styling
            heroTabs.forEach(function(t) {
                t.classList.remove('border-b-2', 'border-secondary', 'bg-white', 'text-foreground');
                t.classList.add('bg-gray-50', 'text-foreground/60', 'hover:bg-gray-100');
            });
            this.classList.remove('bg-gray-50', 'text-foreground/60', 'hover:bg-gray-100');
            this.classList.add('border-b-2', 'border-secondary', 'bg-white', 'text-foreground');

            // Update hidden input
            if (categoryInput) {
                categoryInput.value = category;
            }
        });
    });

    // Location selector button sync with hidden select
    const locationBtn = document.getElementById('location-selector-btn');
    const locationSelect = document.getElementById('pickup-location');
    const selectedLocationSpan = document.getElementById('selected-location');

    // Button click opens select
    if (locationBtn && locationSelect) {
        locationBtn.addEventListener('click', function() {
            locationSelect.click();
        });
    }

    // Update button text when select changes
    if (locationSelect && selectedLocationSpan) {
        locationSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            selectedLocationSpan.textContent = selectedOption.text;
        });
    }

    // Initialize date pickers
    const today = new Date().toISOString().split('T')[0];
    const pickupDate = document.getElementById('pickup-date');
    const returnDate = document.getElementById('return-date');

    if (pickupDate) {
        pickupDate.setAttribute('min', today);
        pickupDate.addEventListener('change', function() {
            if (returnDate) {
                returnDate.setAttribute('min', this.value);
            }
        });
    }

    // Form submission
    const searchForm = document.getElementById('hero-search-form');
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const params = new URLSearchParams();

            for (const [key, value] of formData.entries()) {
                if (value && value !== 'return-location') {
                    params.append(key, value);
                }
            }

            const url = '<?php echo get_post_type_archive_link('vehicle'); ?>';

            if (params.toString()) {
                window.location.href = url + '?' + params.toString();
            } else {
                window.location.href = url;
            }
        });
    }

    // Check URL parameters for pre-filled values
    const urlParams = new URLSearchParams(window.location.search);

    // Pre-fill category
    if (urlParams.has('category') && categoryInput) {
        const category = urlParams.get('category');
        categoryInput.value = category;
        heroTabs.forEach(function(tab) {
            const tabCategory = tab.getAttribute('data-category');
            if (tabCategory === category) {
                tab.click();
            }
        });
    }

    // Pre-fill location
    if (urlParams.has('pickup_location') && locationSelect && selectedLocationSpan) {
        locationSelect.value = urlParams.get('pickup_location');
        const selectedOption = locationSelect.options[locationSelect.selectedIndex];
        selectedLocationSpan.textContent = selectedOption.text;
    }

    // Pre-fill dates
    if (urlParams.has('pickup_date') && pickupDate) {
        pickupDate.value = urlParams.get('pickup_date');
    }
    if (urlParams.has('return_date') && returnDate) {
        returnDate.value = urlParams.get('return_date');
    }

    // Pre-fill times
    if (urlParams.has('pickup_time')) {
        document.getElementById('pickup-time').value = urlParams.get('pickup_time');
    }
    if (urlParams.has('return_time')) {
        document.getElementById('return-time').value = urlParams.get('return_time');
    }
});
</script>
