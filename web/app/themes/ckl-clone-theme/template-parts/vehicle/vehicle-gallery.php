<?php
/**
 * Vehicle Gallery Template Part
 *
 * Displays vehicle image gallery with lightbox support
 *
 * @package CKL_Car_Rental
 */

extract($args);

// Get gallery images
$gallery_ids = get_post_meta(get_the_ID(), '_vehicle_gallery', true);
if (!is_array($gallery_ids)) {
    $gallery_ids = array();
}

// Get featured image
$featured_id = get_post_thumbnail_id();

// Combine: featured image first, then gallery images (excluding duplicates)
$all_images = array();
if ($featured_id) {
    $all_images[] = $featured_id;
}

foreach ($gallery_ids as $gid) {
    if ($gid != $featured_id) {
        $all_images[] = $gid;
    }
}

if (empty($all_images)) {
    return;
}

// Get image data
$images_data = array();
foreach ($all_images as $img_id) {
    $img_data = wp_get_attachment_image_src($img_id, 'large');
    $img_thumb = wp_get_attachment_image_src($img_id, 'thumbnail');
    $img_alt = get_post_meta($img_id, '_wp_attachment_image_alt', true);

    if ($img_data && $img_thumb) {
        $images_data[] = array(
            'id' => $img_id,
            'large' => $img_data[0],
            'thumbnail' => $img_thumb[0],
            'alt' => $img_alt ?: get_the_title(),
            'width' => $img_data[1],
            'height' => $img_data[2],
        );
    }
}

if (empty($images_data)) {
    return;
}

// First image is the main image
$main_image = $images_data[0];
$thumbnail_images = array_slice($images_data, 1);
?>

<div class="ckl-vehicle-gallery rounded-lg border bg-card shadow-sm overflow-hidden">
    <!-- Main Image -->
    <div class="ckl-gallery-main">
        <img src="<?php echo esc_url($main_image['large']); ?>"
             alt="<?php echo esc_attr($main_image['alt']); ?>"
             data-image-index="0"
             class="ckl-gallery-main-image w-full h-[300px] md:h-[500px] object-cover cursor-zoom-in">
    </div>

    <!-- Thumbnail Gallery -->
    <?php if (!empty($thumbnail_images)) : ?>
        <div class="ckl-gallery-thumbnails p-4 flex gap-3 overflow-x-auto">
            <!-- Featured thumbnail (always visible) -->
            <div class="ckl-thumbnail-item flex-shrink-0">
                <img src="<?php echo esc_url($main_image['thumbnail']); ?>"
                     alt="<?php echo esc_attr($main_image['alt']); ?>"
                     data-image-index="0"
                     data-full-url="<?php echo esc_url($main_image['large']); ?>"
                     class="ckl-gallery-thumb w-20 h-20 md:w-24 md:h-24 object-cover rounded border-2 border-primary cursor-pointer transition-all hover:opacity-80">
            </div>

            <?php foreach ($thumbnail_images as $index => $thumb) : ?>
                <div class="ckl-thumbnail-item flex-shrink-0">
                    <img src="<?php echo esc_url($thumb['thumbnail']); ?>"
                         alt="<?php echo esc_attr($thumb['alt']); ?>"
                         data-image-index="<?php echo $index + 1; ?>"
                         data-full-url="<?php echo esc_url($thumb['large']); ?>"
                         class="ckl-gallery-thumb w-20 h-20 md:w-24 md:h-24 object-cover rounded border-2 border-transparent cursor-pointer transition-all hover:border-primary hover:opacity-80">
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- Lightbox -->
<div id="ckl-lightbox" class="ckl-lightbox fixed inset-0 bg-black/90 z-50 hidden items-center justify-center">
    <button class="ckl-lightbox-close absolute top-4 right-4 text-white text-4xl hover:text-gray-300 z-50">&times;</button>

    <button class="ckl-lightbox-prev absolute left-4 top-1/2 -translate-y-1/2 text-white text-5xl hover:text-gray-300">&larr;</button>

    <div class="ckl-lightbox-image-container max-w-5xl max-h-[90vh] px-4">
        <img src="" alt="" class="ckl-lightbox-image max-w-full max-h-[90vh] object-contain">
    </div>

    <button class="ckl-lightbox-next absolute right-4 top-1/2 -translate-y-1/2 text-white text-5xl hover:text-gray-300">&rarr;</button>

    <!-- Counter -->
    <div class="ckl-lightbox-counter absolute bottom-4 left-1/2 -translate-x-1/2 text-white text-lg">
        <span class="ckl-current">1</span> / <span class="ckl-total"><?php echo count($images_data); ?></span>
    </div>
</div>

<script>
// Store image data for lightbox
var cklGalleryImages = <?php echo wp_json_encode($images_data); ?>;
</script>

