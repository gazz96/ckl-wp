/**
 * Vehicle Gallery
 *
 * Handles image gallery with lightbox, thumbnails, and navigation
 */

(function($) {
    'use strict';

    var Gallery = {
        currentIndex: 0,
        images: [],
        $lightbox: null,
        $lightboxImage: null,

        init: function() {
            this.images = (typeof cklGalleryImages !== 'undefined') ? cklGalleryImages : [];
            this.$lightbox = $('#ckl-lightbox');
            this.$lightboxImage = this.$lightbox.find('.ckl-lightbox-image');

            if (this.images.length === 0) {
                return;
            }

            this.bindEvents();
            this.initKeyboardNav();
            this.initTouchNav();
        },

        bindEvents: function() {
            var self = this;

            // Thumbnail click - swap main image
            $('.ckl-gallery-thumb').on('click', function(e) {
                e.preventDefault();
                var index = $(this).data('image-index');
                self.swapMainImage(index);
            });

            // Main image click - open lightbox
            $('.ckl-gallery-main-image').on('click', function(e) {
                e.preventDefault();
                self.openLightbox($(this).data('image-index'));
            });

            // Thumbnail click - open lightbox
            $('.ckl-gallery-thumb').on('click', function(e) {
                e.preventDefault();
                var index = $(this).data('image-index');
                self.openLightbox(index);
            });

            // Close lightbox
            this.$lightbox.find('.ckl-lightbox-close').on('click', function(e) {
                e.preventDefault();
                self.closeLightbox();
            });

            // Lightbox background click - close
            this.$lightbox.on('click', function(e) {
                if (e.target === this) {
                    self.closeLightbox();
                }
            });

            // Previous button
            this.$lightbox.find('.ckl-lightbox-prev').on('click', function(e) {
                e.preventDefault();
                self.showPrevImage();
            });

            // Next button
            this.$lightbox.find('.ckl-lightbox-next').on('click', function(e) {
                e.preventDefault();
                self.showNextImage();
            });
        },

        swapMainImage: function(index) {
            if (index < 0 || index >= this.images.length) {
                return;
            }

            var image = this.images[index];
            var $mainImage = $('.ckl-gallery-main-image');

            // Update main image with fade effect
            $mainImage.css('opacity', '0.5');

            setTimeout(function() {
                $mainImage.attr('src', image.large).attr('data-image-index', index);
                $mainImage.css('opacity', '1');
            }, 150);

            // Update thumbnail borders
            $('.ckl-gallery-thumb').removeClass('border-primary').addClass('border-transparent');
            $('.ckl-gallery-thumb[data-image-index="' + index + '"]')
                .removeClass('border-transparent').addClass('border-primary');
        },

        openLightbox: function(index) {
            if (index < 0 || index >= this.images.length) {
                return;
            }

            this.currentIndex = index;
            this.showLightboxImage(index);

            // Show lightbox
            this.$lightbox.removeClass('hidden').addClass('flex');

            // Prevent body scroll
            $('body').css('overflow', 'hidden');
        },

        closeLightbox: function() {
            this.$lightbox.removeClass('flex').addClass('hidden');
            $('body').css('overflow', '');
        },

        showLightboxImage: function(index) {
            if (index < 0 || index >= this.images.length) {
                return;
            }

            var image = this.images[index];
            this.$lightboxImage.attr('src', image.large).attr('alt', image.alt);

            // Update counter
            this.$lightbox.find('.ckl-current').text(index + 1);
            this.$lightbox.find('.ckl-total').text(this.images.length);

            // Update navigation buttons visibility
            this.$lightbox.find('.ckl-lightbox-prev').toggle(index > 0);
            this.$lightbox.find('.ckl-lightbox-next').toggle(index < this.images.length - 1);
        },

        showPrevImage: function() {
            if (this.currentIndex > 0) {
                this.currentIndex--;
                this.showLightboxImage(this.currentIndex);
            }
        },

        showNextImage: function() {
            if (this.currentIndex < this.images.length - 1) {
                this.currentIndex++;
                this.showLightboxImage(this.currentIndex);
            }
        },

        initKeyboardNav: function() {
            var self = this;

            $(document).on('keydown', function(e) {
                // Only handle if lightbox is open
                if (self.$lightbox.hasClass('hidden')) {
                    return;
                }

                switch(e.key) {
                    case 'Escape':
                        self.closeLightbox();
                        break;
                    case 'ArrowLeft':
                        self.showPrevImage();
                        break;
                    case 'ArrowRight':
                        self.showNextImage();
                        break;
                }
            });
        },

        initTouchNav: function() {
            var self = this;
            var touchStartX = 0;
            var touchEndX = 0;

            this.$lightbox.on('touchstart', function(e) {
                touchStartX = e.originalEvent.changedTouches[0].screenX;
            });

            this.$lightbox.on('touchend', function(e) {
                touchEndX = e.originalEvent.changedTouches[0].screenX;
                self.handleSwipe();
            });

            this.handleSwipe = function() {
                var swipeThreshold = 50;
                var diff = touchStartX - touchEndX;

                if (Math.abs(diff) > swipeThreshold) {
                    if (diff > 0) {
                        // Swipe left - show next image
                        self.showNextImage();
                    } else {
                        // Swipe right - show previous image
                        self.showPrevImage();
                    }
                }
            };
        }
    };

    // Initialize on document ready
    $(document).ready(function() {
        Gallery.init();
    });

    // Make available globally
    window.CKLVehicleGallery = Gallery;

})(jQuery);
