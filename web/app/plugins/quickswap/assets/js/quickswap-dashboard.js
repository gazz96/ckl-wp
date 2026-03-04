/**
 * QuickSwap Modern Admin Dashboard JavaScript
 *
 * Handles AJAX data fetching, widget rendering, and auto-refresh
 *
 * @package QuickSwap
 * @since 1.2.0
 */

(function($) {
    'use strict';

    /**
     * QuickSwap Dashboard object
     */
    var QuickSwapDashboard = {
        /**
         * Initialize
         */
        init: function() {
            this.cacheElements();
            this.bindEvents();
            this.startAutoRefresh();
        },

        /**
         * Cache DOM elements
         */
        cacheElements: function() {
            this.$widgets = $('.quickswap-dashboard-widget');
            this.$document = $(document);
        },

        /**
         * Bind event handlers
         */
        bindEvents: function() {
            var self = this;

            // Manual refresh on widget click
            this.$document.on('click', '.quickswap-widget-refresh', function(e) {
                e.preventDefault();
                var widgetId = $(this).closest('.quickswap-dashboard-widget').data('widget-id');
                if (widgetId) {
                    self.refreshWidget(widgetId);
                }
            });

            // Handle AJAX errors with retry
            this.$document.on('ajaxError', function(event, jqxhr, settings, thrownError) {
                if (settings.data && settings.data.indexOf('action=quickswap_dashboard') !== -1) {
                    self.handleAjaxError(thrownError);
                }
            });

            // Initialize sortable widgets
            this.initSortable();
        },

        /**
         * Initialize sortable widgets (if available)
         */
        initSortable: function() {
            // WordPress already handles sortable on dashboard
            // This is for any custom drag-and-drop we might add
            if (typeof $.fn.sortable !== 'undefined') {
                // Additional sortable customization can go here
            }
        },

        /**
         * Refresh a specific widget
         */
        refreshWidget: function(widgetId) {
            var self = this;
            var $widget = $('[data-widget-id="' + widgetId + '"]').closest('.postbox');

            if (!$widget.length) {
                return;
            }

            // Add loading state
            $widget.addClass('quickswap-loading');

            // Make AJAX request
            $.ajax({
                url: quickswapDashboard.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'quickswap_dashboard_refresh',
                    nonce: quickswapDashboard.nonce,
                    widget_id: widgetId
                },
                timeout: 15000,
                success: function(response) {
                    if (response.success) {
                        self.updateWidgetContent($widget, response.data.html);
                        self.showSuccessMessage($widget);
                    } else {
                        self.showErrorMessage($widget, response.data.message || quickswapDashboard.strings.error);
                    }
                },
                error: function(xhr, status, error) {
                    self.showErrorMessage($widget, quickswapDashboard.strings.error);
                },
                complete: function() {
                    $widget.removeClass('quickswap-loading');
                }
            });
        },

        /**
         * Update widget content
         */
        updateWidgetContent: function($widget, html) {
            var $inside = $widget.find('.inside');
            if ($inside.length) {
                $inside.html(html);
                // Trigger custom event for other scripts
                $inside.trigger('quickswap:widget:updated');
            }
        },

        /**
         * Show success message
         */
        showSuccessMessage: function($widget) {
            var $inside = $widget.find('.inside');
            var $notice = $('<div class="notice notice-success is-dismissible quickswap-refresh-notice" style="margin: 0 0 15px 0;">' +
                '<p>' + quickswapDashboard.strings.refreshSuccess + '</p>' +
                '</div>');

            $inside.prepend($notice);

            // Auto-remove after 3 seconds
            setTimeout(function() {
                $notice.fadeOut(function() {
                    $(this).remove();
                });
            }, 3000);

            // Handle dismiss button
            $notice.on('click', '.notice-dismiss', function() {
                $notice.fadeOut(function() {
                    $(this).remove();
                });
            });
        },

        /**
         * Show error message
         */
        showErrorMessage: function($widget, message) {
            var $inside = $widget.find('.inside');
            var $notice = $('<div class="notice notice-error is-dismissible quickswap-refresh-notice" style="margin: 0 0 15px 0;">' +
                '<p>' + message + '</p>' +
                '</div>');

            $inside.prepend($notice);

            // Handle dismiss button
            $notice.on('click', '.notice-dismiss', function() {
                $notice.fadeOut(function() {
                    $(this).remove();
                });
            });
        },

        /**
         * Handle AJAX errors
         */
        handleAjaxError: function(error) {
            console.error('QuickSwap Dashboard AJAX Error:', error);
        },

        /**
         * Start auto-refresh
         */
        startAutoRefresh: function() {
            var self = this;
            var refreshInterval = quickswapDashboard.refreshInterval || 300000; // Default 5 minutes

            // Set interval for auto-refresh
            this.refreshTimer = setInterval(function() {
                self.refreshAllWidgets();
            }, refreshInterval);
        },

        /**
         * Refresh all widgets
         */
        refreshAllWidgets: function() {
            var self = this;

            this.$widgets.each(function() {
                var widgetId = $(this).data('widget-id');
                if (widgetId) {
                    // Only refresh visible widgets
                    var $widget = $(this).closest('.postbox');
                    if ($widget.is(':visible')) {
                        self.refreshWidget(widgetId);
                    }
                }
            });
        },

        /**
         * Stop auto-refresh
         */
        stopAutoRefresh: function() {
            if (this.refreshTimer) {
                clearInterval(this.refreshTimer);
                this.refreshTimer = null;
            }
        },

        /**
         * Get widget data without updating HTML
         */
        getWidgetData: function(widgetId, callback) {
            $.ajax({
                url: quickswapDashboard.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'quickswap_dashboard_data',
                    nonce: quickswapDashboard.nonce,
                    widget_id: widgetId
                },
                success: function(response) {
                    if (typeof callback === 'function') {
                        callback(response.success ? response.data : null, response);
                    }
                },
                error: function(xhr, status, error) {
                    if (typeof callback === 'function') {
                        callback(null, { success: false, message: error });
                    }
                }
            });
        },

        /**
         * Format date
         */
        formatDate: function(dateString, format) {
            var date = new Date(dateString);
            var formats = {
                'short': date.toLocaleDateString(),
                'long': date.toLocaleDateString(undefined, {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                }),
                'time': date.toLocaleTimeString(undefined, {
                    hour: '2-digit',
                    minute: '2-digit'
                }),
                'datetime': date.toLocaleString()
            };
            return formats[format] || formats['short'];
        },

        /**
         * Debounce function
         */
        debounce: function(func, wait) {
            var timeout;
            return function executedFunction() {
                var context = this;
                var args = arguments;
                clearTimeout(timeout);
                timeout = setTimeout(function() {
                    func.apply(context, args);
                }, wait);
            };
        }
    };

    /**
     * Initialize on document ready
     */
    $(document).ready(function() {
        // Only initialize if we're on the dashboard page
        if ($('.quickswap-dashboard-widget').length || $('body.index-php').length) {
            QuickSwapDashboard.init();
        }

        // Make it globally available
        window.QuickSwapDashboard = QuickSwapDashboard;
    });

    /**
     * Handle page visibility changes
     * Pause auto-refresh when tab is not visible
     */
    document.addEventListener('visibilitychange', function() {
        if (document.hidden) {
            // Pause auto-refresh when tab is hidden
            if (window.QuickSwapDashboard) {
                window.QuickSwapDashboard.stopAutoRefresh();
            }
        } else {
            // Resume auto-refresh when tab is visible
            if (window.QuickSwapDashboard) {
                window.QuickSwapDashboard.startAutoRefresh();
            }
        }
    });

    /**
     * Add refresh button to widgets (optional enhancement)
     */
    $(document).ready(function() {
        $('.quickswap-dashboard-widget').each(function() {
            var $widget = $(this);
            var $handle = $widget.closest('.postbox').find('.hndle');

            if ($handle.length && !$handle.find('.quickswap-widget-refresh').length) {
                var $refreshBtn = $('<button type="button" class="quickswap-widget-refresh button-link" style="float: right; margin-right: 10px;" aria-label="' + quickswapDashboard.strings.refreshSuccess + '">↻</button>');
                $handle.append($refreshBtn);
            }
        });
    });

    /**
     * Progress bar animation
     */
    $(document).on('quickswap:widget:updated', '.inside', function() {
        // Animate progress bars
        $(this).find('.quickswap-progress-fill').each(function() {
            var $fill = $(this);
            var width = $fill.css('width');

            // Reset to 0 for animation
            $fill.css('width', '0');

            // Animate to actual width
            setTimeout(function() {
                $fill.css('transition', 'width 0.6s ease-out');
                $fill.css('width', width);
            }, 50);
        });

        // Animate pie chart segments
        $(this).find('.quickswap-pie-svg circle').each(function(index) {
            var $circle = $(this);
            var dashArray = $circle.attr('stroke-dasharray');

            // Add animation
            $circle.css('transition', 'stroke-dasharray 0.8s ease-out');
        });
    });

    /**
     * Initialize on first load
     */
    $(document).ready(function() {
        // Trigger animation for initial progress bars
        $('.quickswap-progress-fill').css('transition', 'none').each(function() {
            var $fill = $(this);
            var width = $fill.css('width');
            $fill.css('width', '0');
            setTimeout(function() {
                $fill.css('transition', 'width 0.6s ease-out');
                $fill.css('width', width);
            }, 100);
        });
    });

    /**
     * Handle window resize for responsive adjustments
     */
    var resizeTimer;
    $(window).on('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
            // Adjust widget layouts if needed
            if (window.innerWidth < 768) {
                // Mobile adjustments
            } else if (window.innerWidth < 1024) {
                // Tablet adjustments
            }
        }, 250);
    });

})(jQuery);
