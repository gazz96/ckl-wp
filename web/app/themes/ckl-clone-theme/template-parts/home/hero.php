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

$background_images = $hero_settings['background_images'];
$has_slideshow = !empty($background_images);

// Default gradient if no images
$background_style = '';
if ($has_slideshow) {
    $background_style = 'style="background-image: url(\'' . esc_url($background_images[0]) . '\');"';
}
?>

<section class="hero-section relative bg-gradient-to-r from-blue-600 to-blue-800 text-white py-20 overflow-hidden" <?php echo $background_style; ?>>
    <!-- Overlay -->
    <?php if (!empty($hero_settings['overlay_opacity'])) : ?>
        <div class="absolute inset-0 bg-black" style="opacity: <?php echo $hero_settings['overlay_opacity'] / 100; ?>;"></div>
    <?php endif; ?>

    <!-- Background Slideshow -->
    <?php if ($has_slideshow && count($background_images) > 1) : ?>
        <div class="hero-slideshow absolute inset-0">
            <?php foreach ($background_images as $i => $image) : ?>
                <div class="hero-slide absolute inset-0 bg-cover bg-center transition-opacity duration-1000"
                     style="background-image: url('<?php echo esc_url($image); ?>'); opacity: <?php echo $i === 0 ? '1' : '0'; ?>;"
                     data-slide="<?php echo $i; ?>">
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

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
                <div class="bg-white rounded-xl shadow-2xl max-w-5xl mx-auto overflow-hidden text-gray-800 animate-slide-up">
                    <!-- Category Tabs -->
                    <div class="category-tabs-container grid grid-cols-2 border-b">
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
                                <select name="pickup_location" id="pickup-location"
                                        class="w-full px-4 py-2 border rounded-md h-10 text-sm border-border/50 focus:border-secondary focus:ring-2 focus:ring-secondary/20">
                                    <optgroup label="<?php _e('Free Locations', 'ckl-car-rental'); ?>">
                                        <?php if (class_exists('CKL_Hero_Search_Locations')) : ?>
                                            <?php foreach (CKL_Hero_Search_Locations::get_all_locations()['free'] as $slug => $loc): ?>
                                                <option value="<?php echo esc_attr($slug); ?>">
                                                    <?php echo esc_html($loc['name']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </optgroup>
                                    <optgroup label="<?php _e('Custom Locations (+Fee)', 'ckl-car-rental'); ?>">
                                        <?php if (class_exists('CKL_Hero_Search_Locations')) : ?>
                                            <?php foreach (CKL_Hero_Search_Locations::get_all_locations()['custom'] as $slug => $loc): ?>
                                                <option value="<?php echo esc_attr($slug); ?>">
                                                    <?php echo esc_html($loc['name']); ?> (+RM<?php echo $loc['fee']; ?>)
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </optgroup>
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
                                       class="w-full px-3 py-2 border rounded-md h-10 text-sm border-border/50 focus:border-secondary">
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
                                       class="w-full px-3 py-2 border rounded-md h-10 text-sm border-border/50 focus:border-secondary">
                            </div>

                            <!-- Return Date -->
                            <div class="flex-1 min-w-[140px]">
                                <label class="block text-sm font-medium mb-2" for="return-date">
                                    <?php _e('Return Date', 'ckl-car-rental'); ?>
                                </label>
                                <input type="date"
                                       name="return_date"
                                       id="return-date"
                                       class="w-full px-3 py-2 border rounded-md h-10 text-sm border-border/50 focus:border-secondary">
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
                                       class="w-full px-3 py-2 border rounded-md h-10 text-sm border-border/50 focus:border-secondary">
                            </div>

                            <!-- Search Button -->
                            <div class="flex-shrink-0">
                                <button type="submit"
                                        class="inline-flex items-center justify-center gap-2 bg-secondary hover:bg-secondary/90 text-white h-10 px-6 text-sm font-semibold rounded-md transition-all">
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
                                   id="different-return-location"
                                   class="w-4 h-4 rounded border-gray-300 accent-secondary">
                            <label for="different-return-location" class="text-sm text-foreground/70">
                                <?php _e('Return to another location', 'ckl-car-rental'); ?>
                            </label>
                        </div>

                        <!-- Return Location Dropdown (Hidden by default) -->
                        <div id="return-location-container" class="mt-3 hidden">
                            <div class="flex-1 min-w-[200px]">
                                <label class="block text-sm font-medium mb-2" for="return-location">
                                    <?php _e('Return Location', 'ckl-car-rental'); ?>
                                </label>
                                <select name="return_location" id="return-location"
                                        class="w-full px-4 py-2 border rounded-md h-10 text-sm border-border/50 focus:border-secondary focus:ring-2 focus:ring-secondary/20">
                                    <optgroup label="<?php _e('Free Locations', 'ckl-car-rental'); ?>">
                                        <?php if (class_exists('CKL_Hero_Search_Locations')) : ?>
                                            <?php foreach (CKL_Hero_Search_Locations::get_all_locations()['free'] as $slug => $loc): ?>
                                                <option value="<?php echo esc_attr($slug); ?>">
                                                    <?php echo esc_html($loc['name']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </optgroup>
                                    <optgroup label="<?php _e('Custom Locations (+Fee)', 'ckl-car-rental'); ?>">
                                        <?php if (class_exists('CKL_Hero_Search_Locations')) : ?>
                                            <?php foreach (CKL_Hero_Search_Locations::get_all_locations()['custom'] as $slug => $loc): ?>
                                                <option value="<?php echo esc_attr($slug); ?>">
                                                    <?php echo esc_html($loc['name']); ?> (+RM<?php echo $loc['fee']; ?>)
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </optgroup>
                                </select>
                            </div>

                            <!-- Drop-off Fee Display -->
                            <div id="dropoff-fee-display" class="mt-2 text-sm text-orange-600 hidden">
                                <?php _e('Drop-off fee: RM', 'ckl-car-rental'); ?> <span id="fee-amount">0</span>
                            </div>
                        </div>
                    </form>
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
    // Background slideshow
    (function() {
        const slides = document.querySelectorAll('.hero-slide');
        let currentSlide = 0;
        const interval = 5000; // 5 seconds

        function nextSlide() {
            slides[currentSlide].style.opacity = '0';
            currentSlide = (currentSlide + 1) % slides.length;
            slides[currentSlide].style.opacity = '1';
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

    // Different return location checkbox
    const differentLocationCheckbox = document.getElementById('different-return-location');
    const returnLocationContainer = document.getElementById('return-location-container');
    const returnLocationSelect = document.getElementById('return-location');
    const pickupLocationSelect = document.getElementById('pickup-location');
    const feeDisplay = document.getElementById('dropoff-fee-display');
    const feeAmount = document.getElementById('fee-amount');

    if (differentLocationCheckbox && returnLocationContainer) {
        differentLocationCheckbox.addEventListener('change', function() {
            if (this.checked) {
                returnLocationContainer.classList.remove('hidden');
                // Copy pickup location to return location by default
                if (pickupLocationSelect && returnLocationSelect) {
                    returnLocationSelect.value = pickupLocationSelect.value;
                }
                // Trigger fee calculation
                updateDropoffFee();
            } else {
                returnLocationContainer.classList.add('hidden');
                if (feeDisplay) {
                    feeDisplay.classList.add('hidden');
                }
            }
        });
    }

    // Calculate drop-off fee on location change
    function updateDropoffFee() {
        if (!pickupLocationSelect || !returnLocationSelect || !feeDisplay || !feeAmount) return;

        const pickup = pickupLocationSelect.value;
        const returnLoc = returnLocationSelect.value;

        // AJAX call to calculate fee
        fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'action=ckl_calculate_dropoff_fee&pickup=' + encodeURIComponent(pickup) + '&return=' + encodeURIComponent(returnLoc) + '&nonce=<?php echo wp_create_nonce('ckl_dropoff_fee'); ?>'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.data.fee > 0) {
                feeAmount.textContent = data.data.fee;
                feeDisplay.classList.remove('hidden');
            } else {
                feeDisplay.classList.add('hidden');
            }
        })
        .catch(error => {
            console.error('Error calculating fee:', error);
        });
    }

    if (pickupLocationSelect) {
        pickupLocationSelect.addEventListener('change', updateDropoffFee);
    }
    if (returnLocationSelect) {
        returnLocationSelect.addEventListener('change', updateDropoffFee);
    }

    // Form submission
    const searchForm = document.getElementById('hero-search-form');
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const params = new URLSearchParams();

            for (const [key, value] of formData.entries()) {
                if (value && value !== 'different-return-location') {
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

    // Pre-fill locations
    if (urlParams.has('pickup_location') && pickupLocationSelect) {
        pickupLocationSelect.value = urlParams.get('pickup_location');
    }
    if (urlParams.has('return_location') && returnLocationSelect) {
        returnLocationSelect.value = urlParams.get('return_location');
        differentLocationCheckbox.checked = true;
        returnLocationContainer.classList.remove('hidden');
        updateDropoffFee();
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
