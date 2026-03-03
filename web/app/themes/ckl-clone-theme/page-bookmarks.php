<?php
/**
 * Template Name: Bookmarks
 *
 * Bookmarks page template for CK Langkawi Car Rental
 * Displays user's saved/bookmarked vehicles
 */

// Redirect if not logged in
if (!is_user_logged_in()) {
    wp_redirect(wp_login_url(get_permalink()));
    exit;
}

get_header();

$user_id = get_current_user_id();
$bookmarks = get_user_meta($user_id, '_vehicle_bookmarks', true);
$bookmarks = is_array($bookmarks) ? $bookmarks : array();
?>

<!-- Hero Section -->
<section class="hero-section relative bg-gradient-to-r from-blue-600 to-blue-800 text-white py-16">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto text-center">
            <h1 class="text-4xl font-bold mb-4">
                <?php _e('My Bookmarks', 'ckl-car-rental'); ?>
            </h1>
            <p class="text-lg">
                <?php _e('Your saved vehicles for quick reference', 'ckl-car-rental'); ?>
            </p>
        </div>
    </div>
</section>

<!-- Bookmarks Content -->
<section class="bookmarks-content py-12">
    <div class="container mx-auto px-4">
        <div class="max-w-7xl mx-auto">

            <?php if (empty($bookmarks)) : ?>
                <!-- Empty State -->
                <div class="text-center py-16">
                    <div class="text-6xl mb-6">🔖</div>
                    <h2 class="text-2xl font-bold mb-4 text-gray-700">
                        <?php _e('No Bookmarked Vehicles Yet', 'ckl-car-rental'); ?>
                    </h2>
                    <p class="text-gray-600 mb-8 max-w-md mx-auto">
                        <?php _e('Start bookmarking your favorite vehicles by clicking the bookmark icon on any vehicle listing.', 'ckl-car-rental'); ?>
                    </p>
                    <a href="<?php echo get_post_type_archive_link('vehicle'); ?>"
                       class="inline-block bg-primary text-white px-8 py-3 rounded-lg font-semibold hover:bg-primary/90 transition">
                        <?php _e('Browse Vehicles', 'ckl-car-rental'); ?>
                    </a>
                </div>
            <?php else : ?>
                <!-- Bookmarks List -->
                <div class="mb-6 flex items-center justify-between">
                    <p class="text-gray-600">
                        <?php printf(_n('You have %d bookmarked vehicle', 'You have %d bookmarked vehicles', count($bookmarks), 'ckl-car-rental'), count($bookmarks)); ?>
                    </p>
                    <button id="clear-all-bookmarks"
                            class="text-red-600 hover:text-red-700 font-semibold text-sm">
                        <?php _e('Clear All Bookmarks', 'ckl-car-rental'); ?>
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    <?php
                    $bookmark_args = array(
                        'post_type' => 'vehicle',
                        'posts_per_page' => -1,
                        'post__in' => $bookmarks,
                        'orderby' => 'post__in',
                        'post_status' => 'publish',
                    );

                    $bookmark_query = new WP_Query($bookmark_args);

                    if ($bookmark_query->have_posts()) :
                        while ($bookmark_query->have_posts()) : $bookmark_query->the_post();
                            global $post;
                            ?>
                            <div class="vehicle-card bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition"
                                 data-vehicle-id="<?php echo esc_attr($post->ID); ?>">
                                <?php if (has_post_thumbnail()) : ?>
                                    <div class="relative">
                                        <a href="<?php the_permalink(); ?>">
                                            <?php the_post_thumbnail('medium', array('class' => 'w-full h-48 object-cover')); ?>
                                        </a>
                                        <button class="remove-bookmark absolute top-3 right-3 bg-red-500 text-white w-10 h-10 rounded-full flex items-center justify-center hover:bg-red-600 transition"
                                                data-vehicle-id="<?php echo esc_attr($post->ID); ?>"
                                                title="<?php _e('Remove bookmark', 'ckl-car-rental'); ?>">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>
                                <?php endif; ?>

                                <div class="p-4">
                                    <h3 class="font-bold text-lg mb-2">
                                        <a href="<?php the_permalink(); ?>" class="hover:text-primary">
                                            <?php the_title(); ?>
                                        </a>
                                    </h3>

                                    <?php
                                    $vehicle_type = get_post_meta($post->ID, '_vehicle_type', true);
                                    $transmission = get_post_meta($post->ID, '_transmission', true);
                                    $seats = get_post_meta($post->ID, '_seats', true);
                                    $daily_rate = get_post_meta($post->ID, '_daily_rate', true);
                                    ?>

                                    <div class="flex flex-wrap gap-2 mb-3">
                                        <?php if ($vehicle_type) : ?>
                                            <span class="bg-accent/10 text-primary text-xs px-2 py-1 rounded">
                                                <?php echo esc_html(ucfirst(str_replace('_', ' ', $vehicle_type))); ?>
                                            </span>
                                        <?php endif; ?>
                                        <?php if ($transmission) : ?>
                                            <span class="bg-gray-100 text-gray-800 text-xs px-2 py-1 rounded">
                                                <?php echo esc_html(ucfirst($transmission)); ?>
                                            </span>
                                        <?php endif; ?>
                                        <?php if ($seats) : ?>
                                            <span class="bg-gray-100 text-gray-800 text-xs px-2 py-1 rounded">
                                                <?php printf(__('%d Seats', 'ckl-car-rental'), $seats); ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>

                                    <?php if ($daily_rate) : ?>
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <span class="text-2xl font-bold text-primary">
                                                    RM <?php echo number_format($daily_rate, 0); ?>
                                                </span>
                                                <span class="text-gray-600 text-sm">
                                                    /<?php _e('day', 'ckl-car-rental'); ?>
                                                </span>
                                            </div>
                                            <a href="<?php the_permalink(); ?>"
                                               class="bg-primary text-white px-4 py-2 rounded hover:bg-primary/90 transition text-sm">
                                                <?php _e('View Details', 'ckl-car-rental'); ?>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php
                        endwhile;
                        wp_reset_postdata();
                    else :
                        ?>
                        <!-- No published vehicles found -->
                        <div class="col-span-full text-center py-8">
                            <p class="text-gray-600">
                                <?php _e('Some bookmarked vehicles are no longer available.', 'ckl-car-rental'); ?>
                            </p>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Remove individual bookmark
    const removeButtons = document.querySelectorAll('.remove-bookmark');

    removeButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const vehicleId = this.dataset.vehicleId;
            const card = this.closest('.vehicle-card');

            if (!confirm('<?php _e('Are you sure you want to remove this bookmark?', 'ckl-car-rental'); ?>')) {
                return;
            }

            // AJAX call to remove bookmark
            const formData = new FormData();
            formData.append('action', 'ckl_remove_bookmark');
            formData.append('vehicle_id', vehicleId);
            formData.append('nonce', cklAjax.nonce);

            fetch(cklAjax.ajax_url, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove card from DOM with animation
                    card.style.opacity = '0';
                    card.style.transform = 'scale(0.9)';
                    setTimeout(() => {
                        card.remove();

                        // Update count text
                        const countText = document.querySelector('.bookmarks-content .text-gray-600');
                        if (countText && data.data.count !== undefined) {
                            if (data.data.count === 0) {
                                location.reload();
                            } else {
                                countText.textContent = data.data.count + ' ' +
                                    (data.data.count === 1 ?
                                        '<?php _e('bookmarked vehicle', 'ckl-car-rental'); ?>' :
                                        '<?php _e('bookmarked vehicles', 'ckl-car-rental'); ?>');
                            }
                        }
                    }, 300);
                } else {
                    alert('<?php _e('Failed to remove bookmark. Please try again.', 'ckl-car-rental'); ?>');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('<?php _e('An error occurred. Please try again.', 'ckl-car-rental'); ?>');
            });
        });
    });

    // Clear all bookmarks
    const clearAllButton = document.getElementById('clear-all-bookmarks');

    if (clearAllButton) {
        clearAllButton.addEventListener('click', function(e) {
            e.preventDefault();

            if (!confirm('<?php _e('Are you sure you want to remove all bookmarks? This action cannot be undone.', 'ckl-car-rental'); ?>')) {
                return;
            }

            // Get all vehicle IDs
            const vehicleCards = document.querySelectorAll('.vehicle-card');
            const vehicleIds = Array.from(vehicleCards).map(card => card.dataset.vehicleId);

            // Remove each bookmark
            let completed = 0;

            vehicleIds.forEach(vehicleId => {
                const formData = new FormData();
                formData.append('action', 'ckl_remove_bookmark');
                formData.append('vehicle_id', vehicleId);
                formData.append('nonce', cklAjax.nonce);

                fetch(cklAjax.ajax_url, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    completed++;
                    if (completed === vehicleIds.length) {
                        location.reload();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    completed++;
                    if (completed === vehicleIds.length) {
                        location.reload();
                    }
                });
            });
        });
    }
});
</script>

<?php get_footer(); ?>
