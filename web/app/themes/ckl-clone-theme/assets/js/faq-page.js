/**
 * FAQ Page JavaScript
 *
 * @package CKL_Car_Rental
 */

(function() {
    'use strict';

    /**
     * Initialize FAQ accordion functionality
     */
    function initFAQAccordion() {
        const faqTriggers = document.querySelectorAll('.faq-trigger');

        faqTriggers.forEach(trigger => {
            trigger.addEventListener('click', function() {
                const faqItem = this.closest('.faq-item');
                const content = faqItem.querySelector('.faq-content');
                const icon = this.querySelector('.faq-icon');
                const isExpanded = this.getAttribute('aria-expanded') === 'true';

                // Close all other FAQ items (accordion behavior)
                faqTriggers.forEach(otherTrigger => {
                    if (otherTrigger !== trigger) {
                        const otherItem = otherTrigger.closest('.faq-item');
                        const otherContent = otherItem.querySelector('.faq-content');
                        const otherIcon = otherTrigger.querySelector('.faq-icon');

                        otherTrigger.setAttribute('aria-expanded', 'false');
                        otherContent.setAttribute('aria-hidden', 'true');
                        otherContent.style.maxHeight = '0';
                        otherIcon.style.transform = 'rotate(0deg)';
                        otherContent.classList.add('hidden');
                    }
                });

                // Toggle current FAQ item
                if (isExpanded) {
                    // Close
                    this.setAttribute('aria-expanded', 'false');
                    content.setAttribute('aria-hidden', 'true');
                    content.style.maxHeight = '0';
                    icon.style.transform = 'rotate(0deg)';
                    content.classList.add('hidden');
                } else {
                    // Open
                    this.setAttribute('aria-expanded', 'true');
                    content.setAttribute('aria-hidden', 'false');
                    content.classList.remove('hidden');
                    // Set max-height for smooth animation
                    content.style.maxHeight = content.scrollHeight + 'px';
                    icon.style.transform = 'rotate(180deg)';
                }
            });

            // Keyboard accessibility
            trigger.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    this.click();
                }
            });
        });

        // Handle window resize to adjust max-height
        window.addEventListener('resize', function() {
            const openContent = document.querySelector('.faq-content[aria-hidden="false"]');
            if (openContent) {
                openContent.style.maxHeight = 'none';
                const height = openContent.scrollHeight;
                openContent.style.maxHeight = height + 'px';
            }
        });
    }

    /**
     * Initialize FAQ category filter functionality
     */
    function initFAQFilters() {
        const filterButtons = document.querySelectorAll('.faq-filter-btn');
        const faqItems = document.querySelectorAll('.faq-item');

        filterButtons.forEach(button => {
            button.addEventListener('click', function() {
                const selectedCategory = this.getAttribute('data-category');

                // Update button states
                filterButtons.forEach(btn => {
                    btn.classList.remove('bg-accent', 'hover:bg-accent/90', 'text-white');
                    btn.classList.add('border', 'border-input', 'bg-background', 'hover:bg-accent', 'hover:text-accent-foreground');
                });

                // Set active state
                this.classList.remove('border', 'border-input', 'bg-background', 'hover:bg-accent', 'hover:text-accent-foreground');
                this.classList.add('bg-accent', 'hover:bg-accent/90', 'text-white');

                // Filter FAQ items
                faqItems.forEach(item => {
                    const itemCategories = item.getAttribute('data-categories');

                    if (selectedCategory === 'all' || itemCategories.includes(selectedCategory)) {
                        item.style.display = 'block';
                        // Add fade-in animation
                        item.style.animation = 'fadeIn 0.3s ease-in-out';
                    } else {
                        item.style.display = 'none';
                        // Close any open items
                        const content = item.querySelector('.faq-content');
                        const trigger = item.querySelector('.faq-trigger');
                        const icon = item.querySelector('.faq-icon');

                        if (content) {
                            content.setAttribute('aria-hidden', 'true');
                            content.style.maxHeight = '0';
                            content.classList.add('hidden');
                        }
                        if (trigger) {
                            trigger.setAttribute('aria-expanded', 'false');
                        }
                        if (icon) {
                            icon.style.transform = 'rotate(0deg)';
                        }
                    }
                });

                // Show/hide "no results" message if needed
                const visibleItems = Array.from(faqItems).filter(item => item.style.display !== 'none');
                let noResultsMsg = document.querySelector('.faq-no-results');

                if (visibleItems.length === 0) {
                    if (!noResultsMsg) {
                        noResultsMsg = document.createElement('div');
                        noResultsMsg.className = 'faq-no-results text-center py-12';
                        noResultsMsg.innerHTML = `
                            <div class="text-6xl mb-4">🔍</div>
                            <h2 class="text-2xl font-bold mb-2">No FAQs found</h2>
                            <p class="text-gray-600">Try selecting a different category.</p>
                        `;
                        document.querySelector('.max-w-4xl.mx-auto .space-y-3').appendChild(noResultsMsg);
                    }
                    noResultsMsg.style.display = 'block';
                } else if (noResultsMsg) {
                    noResultsMsg.style.display = 'none';
                }
            });
        });

        // Set initial active state (All button)
        const allButton = document.querySelector('.faq-filter-btn[data-category="all"]');
        if (allButton) {
            allButton.classList.remove('border', 'border-input', 'bg-background', 'hover:bg-accent', 'hover:text-accent-foreground');
            allButton.classList.add('bg-accent', 'hover:bg-accent/90', 'text-white');
        }
    }

    /**
     * Add CSS animations for FAQ
     */
    function addFAQAnimations() {
        const style = document.createElement('style');
        style.textContent = `
            @keyframes fadeIn {
                from {
                    opacity: 0;
                    transform: translateY(-10px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .faq-content {
                transition: max-height 0.3s ease-in-out, opacity 0.3s ease-in-out;
            }

            .faq-icon {
                transition: transform 0.2s ease-in-out;
            }

            .faq-trigger:focus-visible {
                outline: 2px solid currentColor;
                outline-offset: 2px;
            }
        `;
        document.head.appendChild(style);
    }

    /**
     * Initialize all features when DOM is ready
     */
    function initFAQPage() {
        initFAQAccordion();
        initFAQFilters();
        addFAQAnimations();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initFAQPage);
    } else {
        initFAQPage();
    }

})();
