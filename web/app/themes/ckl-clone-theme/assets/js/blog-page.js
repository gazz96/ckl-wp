/**
 * Blog Page JavaScript
 * Handles search, filtering, and interactive features
 */

(function() {
    'use strict';

    /**
     * Initialize search functionality with debouncing
     */
    function initSearch() {
        const searchInput = document.getElementById('blog-search');

        if (!searchInput) {
            return;
        }

        let searchTimeout;

        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);

            // Debounce search: wait 500ms after user stops typing
            searchTimeout = setTimeout(function() {
                const query = searchInput.value.trim();

                if (query.length >= 2) {
                    performSearch(query);
                } else if (query.length === 0) {
                    // Clear search - reload page without query
                    window.location.href = window.location.pathname;
                }
            }, 500);
        });

        // Allow Enter key to trigger immediate search
        searchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                clearTimeout(searchTimeout);
                const query = searchInput.value.trim();
                window.location.href = '?s=' + encodeURIComponent(query);
            }
        });
    }

    /**
     * Perform AJAX search for blog posts
     */
    function performSearch(query) {
        const grid = document.getElementById('blog-posts-grid');
        if (!grid) return;

        // Show loading state
        grid.style.opacity = '0.5';

        // Use WordPress built-in search by redirecting
        // This ensures proper pagination and URL sharing
        window.location.href = '?s=' + encodeURIComponent(query);
    }

    /**
     * Add hover effects to blog cards
     */
    function initCardHoverEffects() {
        const cards = document.querySelectorAll('#blog-posts-grid article');

        cards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-4px)';
            });

            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });
    }

    /**
     * Initialize share buttons
     */
    function initShareButtons() {
        const copyButton = document.querySelector('[onclick*="navigator.clipboard"]');

        if (copyButton) {
            copyButton.addEventListener('click', function(e) {
                e.preventDefault();

                const url = this.getAttribute('data-url') || window.location.href;

                navigator.clipboard.writeText(url).then(function() {
                    // Show success message
                    const originalText = copyButton.innerHTML;
                    copyButton.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>';
                    copyButton.classList.add('bg-green-500', 'text-white');

                    setTimeout(function() {
                        copyButton.innerHTML = originalText;
                        copyButton.classList.remove('bg-green-500', 'text-white');
                    }, 2000);
                }).catch(function(err) {
                    console.error('Failed to copy:', err);
                });
            });
        }
    }

    /**
     * Smooth scroll for anchor links
     */
    function initSmoothScroll() {
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                const href = this.getAttribute('href');

                if (href === '#' || href === '#top') {
                    return;
                }

                const target = document.querySelector(href);

                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    }

    /**
     * Animate elements on scroll
     */
    function initScrollAnimations() {
        const observerOptions = {
            root: null,
            rootMargin: '0px',
            threshold: 0.1
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        // Observe blog cards
        const cards = document.querySelectorAll('#blog-posts-grid article');
        cards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.transition = `opacity 0.5s ease ${index * 0.1}s, transform 0.5s ease ${index * 0.1}s`;
            observer.observe(card);
        });
    }

    /**
     * Initialize newsletter form
     */
    function initNewsletterForm() {
        const form = document.querySelector('form[action*="newsletter"]');

        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                const emailInput = this.querySelector('input[type="email"]');
                const email = emailInput.value.trim();
                const submitButton = this.querySelector('button[type="submit"]');

                if (!email) {
                    alert('Please enter your email address.');
                    return;
                }

                // Simulate newsletter subscription
                // In production, this would send to your newsletter service
                submitButton.textContent = 'Subscribing...';
                submitButton.disabled = true;

                setTimeout(function() {
                    alert('Thank you for subscribing!');
                    form.reset();
                    submitButton.textContent = 'Subscribe';
                    submitButton.disabled = false;
                }, 1500);
            });
        }
    }

    /**
     * Initialize all blog page features
     */
    function init() {
        initSearch();
        initCardHoverEffects();
        initShareButtons();
        initSmoothScroll();
        initScrollAnimations();
        initNewsletterForm();
    }

    // Run on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
