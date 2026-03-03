/**
 * Vehicle Filtering JavaScript
 * Handles AJAX filtering, date pickers, and URL management
 */

(function($) {
    'use strict';

    const VehicleFilters = {
        /**
         * Initialize
         */
        init: function() {
            this.cacheElements();
            this.bindEvents();
            this.initializeDatePickers();
            this.loadFiltersFromURL();
        },

        /**
         * Cache DOM elements
         */
        cacheElements: function() {
            this.$form = $('#vehicle-filter-form');
            this.$grid = $('#vehicle-grid');
            this.$search = $('#search-vehicles');
            this.$pickupDate = $('#pickup-date');
            this.$returnDate = $('#return-date');
            this.$typeCheckboxes = $('.vehicle-type-filter');
            this.$resultsCount = $('.mb-6.text-gray-600'); // Results count element
        },

        /**
         * Bind event handlers
         */
        bindEvents: function() {
            const self = this;

            // Form submission
            if (this.$form.length) {
                this.$form.on('submit', function(e) {
                    e.preventDefault();
                    self.filterVehicles();
                });
            }

            // Search input debounce
            if (this.$search.length) {
                let searchTimeout;
                this.$search.on('input', function() {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(function() {
                        self.filterVehicles();
                    }, 500);
                });
            }

            // Date change
            this.$pickupDate.on('change', function() {
                self.updateReturnDateMin();
                self.filterVehicles();
            });

            this.$returnDate.on('change', function() {
                self.filterVehicles();
            });

            // Vehicle type checkboxes
            this.$typeCheckboxes.on('change', function() {
                self.filterVehicles();
            });

            // Pagination clicks
            $(document).on('click', '.pagination a', function(e) {
                e.preventDefault();
                const paged = $(this).data('paged') || 1;
                self.filterVehicles(paged);
            });

            // Bookmark functionality
            $(document).on('click', '.bookmark-btn', function(e) {
                e.preventDefault();
                self.toggleBookmark($(this));
            });
        },

        /**
         * Initialize date pickers with constraints
         */
        initializeDatePickers: function() {
            const today = new Date().toISOString().split('T')[0];

            // Set minimum pickup date to today
            this.$pickupDate.attr('min', today);

            // Set minimum return date based on pickup
            this.updateReturnDateMin();
        },

        /**
         * Update return date minimum based on pickup date
         */
        updateReturnDateMin: function() {
            const pickupDate = this.$pickupDate.val();
            if (pickupDate) {
                // Add one day to pickup date for minimum return date
                const pickup = new Date(pickupDate);
                pickup.setDate(pickup.getDate() + 1);
                const minReturn = pickup.toISOString().split('T')[0];
                this.$returnDate.attr('min', minReturn);

                // If return date is before new minimum, clear it
                if (this.$returnDate.val() && this.$returnDate.val() < minReturn) {
                    this.$returnDate.val('');
                }
            }
        },

        /**
         * Filter vehicles via AJAX
         */
        filterVehicles: function(paged = 1) {
            const self = this;

            // Show loading state
            this.$grid.addClass('opacity-50');
            this.$grid.append('<div class="loading-overlay absolute inset-0 bg-white/80 flex items-center justify-center"><div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary"></div></div>');

            // Gather filter data
            const formData = {
                nonce: cklVehicleFilters.nonce,
                search: this.$search.val() || '',
                pickup_date: this.$pickupDate.val() || '',
                return_date: this.$returnDate.val() || '',
                vehicle_types: [],
                paged: paged
            };

            // Get selected vehicle types
            this.$typeCheckboxes.filter(':checked').each(function() {
                formData.vehicle_types.push($(this).val());
            });

            // Update URL parameters without reloading
            this.updateURL(formData);

            // AJAX request
            $.ajax({
                url: cklVehicleFilters.ajax_url,
                type: 'POST',
                data: {
                    action: 'ckl_filter_vehicles',
                    ...formData
                },
                success: function(response) {
                    if (response.success) {
                        self.$grid.html(response.data.html);
                        self.updateResultsCount(response.data.count, response.data.found_posts);

                        // Update pagination if it exists
                        if (response.data.pagination) {
                            $('.pagination').parent().replaceWith(response.data.pagination);
                        }

                        // Reinitialize bookmark buttons
                        self.initializeBookmarks();
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', error);
                    self.$grid.html('<div class="col-span-full text-center py-12 text-red-500"><?php _e('Error loading vehicles. Please try again.', 'ckl-car-rental'); ?></div>');
                },
                complete: function() {
                    self.$grid.removeClass('opacity-50');
                    self.$grid.find('.loading-overlay').remove();
                }
            });
        },

        /**
         * Update results count display
         */
        updateResultsCount: function(count, found) {
            const text = count + ' of ' + found + ' vehicles';
            this.$resultsCount.text(text);
        },

        /**
         * Update URL parameters
         */
        updateURL: function(formData) {
            const url = new URL(window.location);

            // Update search parameter
            if (formData.search) {
                url.searchParams.set('s', formData.search);
            } else {
                url.searchParams.delete('s');
            }

            // Update dates
            if (formData.pickup_date) {
                url.searchParams.set('pickup_date', formData.pickup_date);
            } else {
                url.searchParams.delete('pickup_date');
            }

            if (formData.return_date) {
                url.searchParams.set('return_date', formData.return_date);
            } else {
                url.searchParams.delete('return_date');
            }

            // Update vehicle types
            url.searchParams.delete('vehicle_type');
            formData.vehicle_types.forEach(function(type) {
                url.searchParams.append('vehicle_type[]', type);
            });

            // Update browser URL without reloading
            window.history.pushState({}, '', url);
        },

        /**
         * Load filters from URL parameters on page load
         */
        loadFiltersFromURL: function() {
            const urlParams = new URLSearchParams(window.location.search);

            // Load search
            if (urlParams.has('s')) {
                this.$search.val(urlParams.get('s'));
            }

            // Load dates
            if (urlParams.has('pickup_date')) {
                this.$pickupDate.val(urlParams.get('pickup_date'));
            }
            if (urlParams.has('return_date')) {
                this.$returnDate.val(urlParams.get('return_date'));
            }

            // Load vehicle types
            const vehicleTypes = urlParams.getAll('vehicle_type[]');
            vehicleTypes.forEach(function(type) {
                self.$typeCheckboxes.filter('[value="' + type + '"]').prop('checked', true);
            });
        },

        /**
         * Toggle bookmark status
         */
        toggleBookmark: function($btn) {
            const vehicleId = $btn.data('vehicle-id');
            const nonce = $btn.data('nonce');
            const isBookmarked = $btn.data('bookmarked') === '1';
            const self = this;

            // Determine action
            const action = isBookmarked ? 'ckl_remove_bookmark' : 'ckl_add_bookmark';

            $.ajax({
                url: cklVehicleFilters.ajax_url,
                type: 'POST',
                data: {
                    action: action,
                    vehicle_id: vehicleId,
                    nonce: nonce
                },
                success: function(response) {
                    if (response.success) {
                        // Update button state
                        const newState = isBookmarked ? '0' : '1';
                        $btn.data('bookmarked', newState);

                        // Update icon
                        const $svg = $btn.find('svg');
                        if (newState === '1') {
                            $svg.addClass('text-red-500 fill-current');
                            $svg.attr('fill', 'currentColor');
                        } else {
                            $svg.removeClass('text-red-500 fill-current');
                            $svg.attr('fill', 'none');
                        }

                        // Show notification
                        self.showNotification(response.data.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Bookmark Error:', error);
                    self.showNotification('<?php _e('Error updating bookmark. Please try again.', 'ckl-car-rental'); ?>', 'error');
                }
            });
        },

        /**
         * Initialize bookmark buttons from server-rendered HTML
         */
        initializeBookmarks: function() {
            // Bookmarks are already initialized in PHP template
            // This is just a placeholder for any additional JS initialization needed
        },

        /**
         * Show notification message
         */
        showNotification: function(message, type = 'success') {
            const bgColor = type === 'success' ? 'bg-green-500' : 'bg-red-500';
            const notification = $('<div class="fixed top-4 right-4 ' + bgColor + ' text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-fade-in">' + message + '</div>');

            $('body').append(notification);

            setTimeout(function() {
                notification.addClass('animate-fade-out');
                setTimeout(function() {
                    notification.remove();
                }, 300);
            }, 3000);
        }
    };

    // Initialize on document ready
    $(document).ready(function() {
        if ($('#vehicle-filter-form').length || $('#vehicle-grid').length) {
            VehicleFilters.init();
        }
    });

})(jQuery);
