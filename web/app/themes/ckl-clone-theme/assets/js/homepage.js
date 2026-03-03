/**
 * Homepage JavaScript
 *
 * Handles:
 * - Background image slideshow
 * - Search form with calendar integration
 * - Vehicle category tabs
 * - Review carousel
 * - FAQ accordion
 * - Scroll animations
 */

(function() {
    'use strict';

    // Initialize on DOM ready
    document.addEventListener('DOMContentLoaded', function() {
        initHeroSlideshow();
        initSearchForm();
        initVehicleTabs();
        initReviewCarousel();
        initScrollAnimations();
        initSmoothScroll();
    });

    /**
     * Hero Background Slideshow
     */
    function initHeroSlideshow() {
        const slides = document.querySelectorAll('.hero-slide');
        if (slides.length <= 1) return;

        let currentSlide = 0;
        const interval = 5000; // 5 seconds per slide

        function showSlide(index) {
            slides.forEach((slide, i) => {
                slide.style.opacity = i === index ? '1' : '0';
            });
        }

        function nextSlide() {
            currentSlide = (currentSlide + 1) % slides.length;
            showSlide(currentSlide);
        }

        // Start slideshow
        setInterval(nextSlide, interval);

        // Add keyboard navigation
        document.addEventListener('keydown', function(e) {
            if (e.key === 'ArrowRight') {
                currentSlide = (currentSlide + 1) % slides.length;
                showSlide(currentSlide);
            } else if (e.key === 'ArrowLeft') {
                currentSlide = (currentSlide - 1 + slides.length) % slides.length;
                showSlide(currentSlide);
            }
        });
    }

    /**
     * Search Form with Date Validation
     */
    function initSearchForm() {
        const searchForms = document.querySelectorAll('#hero-search-form, .vehicle-search-form');

        searchForms.forEach(function(form) {
            const pickupDateInput = form.querySelector('input[name="pickup_date"]');
            const returnDateInput = form.querySelector('input[name="return_date"]');

            if (!pickupDateInput || !returnDateInput) return;

            // Set minimum dates
            const today = new Date().toISOString().split('T')[0];
            pickupDateInput.setAttribute('min', today);

            // Update return date minimum when pickup date changes
            pickupDateInput.addEventListener('change', function() {
                const pickupDate = new Date(this.value);
                pickupDate.setDate(pickupDate.getDate() + 2); // Minimum 2 days rental
                const minReturnDate = pickupDate.toISOString().split('T')[0];
                returnDateInput.setAttribute('min', minReturnDate);

                // Update return date if it's before the new minimum
                if (returnDateInput.value && returnDateInput.value < minReturnDate) {
                    returnDateInput.value = minReturnDate;
                }
            });

            // Form submission
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(form);
                const params = new URLSearchParams();

                for (const [key, value] of formData.entries()) {
                    if (value) {
                        params.append(key, value);
                    }
                }

                const baseUrl = form.getAttribute('data-base-url') || '/vehicles/';
                const url = baseUrl + (params.toString() ? '?' + params.toString() : '');

                window.location.href = url;
            });
        });
    }

    /**
     * Vehicle Category Tabs
     */
    function initVehicleTabs() {
        const tabs = document.querySelectorAll('.vehicle-tab');
        if (!tabs.length) return;

        tabs.forEach(function(tab) {
            tab.addEventListener('click', function() {
                const category = this.getAttribute('data-category');

                // Update active tab
                tabs.forEach(function(t) {
                    t.classList.remove('bg-blue-600', 'text-white');
                    t.classList.add('text-gray-700', 'hover:bg-gray-100');
                });
                this.classList.remove('text-gray-700', 'hover:bg-gray-100');
                this.classList.add('bg-blue-600', 'text-white');

                // Filter vehicles with animation
                filterVehicles(category);
            });
        });
    }

    /**
     * Filter Vehicles by Category
     */
    function filterVehicles(category) {
        const vehicleCards = document.querySelectorAll('.vehicle-card');

        vehicleCards.forEach(function(card) {
            const cardCategory = card.getAttribute('data-category');

            if (category === 'all' || cardCategory === category) {
                card.style.display = 'block';
                card.style.animation = 'fadeIn 0.3s ease-in-out';
            } else {
                card.style.display = 'none';
            }
        });
    }

    /**
     * Review Carousel (Optional Enhancement)
     */
    function initReviewCarousel() {
        const carousel = document.querySelector('.reviews-carousel');
        if (!carousel) return;

        const track = carousel.querySelector('.reviews-track');
        const items = carousel.querySelectorAll('.review-card');
        const prevBtn = carousel.querySelector('.carousel-prev');
        const nextBtn = carousel.querySelector('.carousel-next');

        if (!track || !items.length) return;

        let currentIndex = 0;
        const itemsPerView = window.innerWidth >= 1024 ? 3 : (window.innerWidth >= 768 ? 2 : 1);
        const maxIndex = Math.max(0, items.length - itemsPerView);

        function updateCarousel() {
            const itemWidth = items[0].offsetWidth;
            const offset = -(currentIndex * (itemWidth + 20)); // 20px gap
            track.style.transform = `translateX(${offset}px)`;

            // Update button states
            if (prevBtn) prevBtn.disabled = currentIndex === 0;
            if (nextBtn) nextBtn.disabled = currentIndex >= maxIndex;
        }

        if (prevBtn) {
            prevBtn.addEventListener('click', function() {
                if (currentIndex > 0) {
                    currentIndex--;
                    updateCarousel();
                }
            });
        }

        if (nextBtn) {
            nextBtn.addEventListener('click', function() {
                if (currentIndex < maxIndex) {
                    currentIndex++;
                    updateCarousel();
                }
            });
        }

        // Auto-advance carousel
        setInterval(function() {
            if (currentIndex < maxIndex) {
                currentIndex++;
            } else {
                currentIndex = 0;
            }
            updateCarousel();
        }, 5000);

        // Update on resize
        window.addEventListener('resize', updateCarousel);
    }

    /**
     * Scroll Animations
     */
    function initScrollAnimations() {
        const animatedElements = document.querySelectorAll('.animate-on-scroll');

        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-fade-in-up');
                    entry.target.style.opacity = '1';
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        animatedElements.forEach(function(element) {
            element.style.opacity = '0';
            observer.observe(element);
        });
    }

    /**
     * Smooth Scroll for Anchor Links
     */
    function initSmoothScroll() {
        document.querySelectorAll('a[href^="#"]').forEach(function(anchor) {
            anchor.addEventListener('click', function(e) {
                const href = this.getAttribute('href');

                if (href === '#') return;

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
     * Sticky Header on Scroll
     */
    let lastScrollTop = 0;
    window.addEventListener('scroll', function() {
        const header = document.querySelector('header');
        if (!header) return;

        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

        if (scrollTop > 100) {
            header.classList.add('shadow-md');
        } else {
            header.classList.remove('shadow-md');
        }

        lastScrollTop = scrollTop;
    }, { passive: true });

    /**
     * Pre-fill dates from URL parameters
     */
    function preFillDates() {
        const urlParams = new URLSearchParams(window.location.search);

        const pickupDate = urlParams.get('pickup_date');
        const returnDate = urlParams.get('return_date');
        const category = urlParams.get('category');

        if (pickupDate) {
            const pickupInput = document.querySelector('input[name="pickup_date"]');
            if (pickupInput) pickupInput.value = pickupDate;
        }

        if (returnDate) {
            const returnInput = document.querySelector('input[name="return_date"]');
            if (returnInput) returnInput.value = returnDate;
        }

        if (category) {
            const categorySelect = document.querySelector('select[name="category"]');
            if (categorySelect) {
                categorySelect.value = category;

                // Trigger tab change if using tabbed interface
                const tab = document.querySelector(`.vehicle-tab[data-category="${category}"]`);
                if (tab) tab.click();
            }
        }
    }

    // Call pre-fill on page load
    preFillDates();

    // Expose functions globally for external use
    window.CKLHomepage = {
        filterVehicles: filterVehicles,
        showSlide: function(index) {
            const slides = document.querySelectorAll('.hero-slide');
            if (slides.length) {
                slides.forEach((slide, i) => {
                    slide.style.opacity = i === index ? '1' : '0';
                });
            }
        }
    };

})();

/**
 * Add CSS animations
 */
const style = document.createElement('style');
style.textContent = `
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fade-in-up {
        animation: fadeIn 0.6s ease-out forwards;
    }

    .hero-slideshow {
        position: absolute;
        inset: 0;
        z-index: 0;
    }

    .hero-slide {
        position: absolute;
        inset: 0;
        background-size: cover;
        background-position: center;
        transition: opacity 1s ease-in-out;
    }

    .reviews-carousel {
        position: relative;
        overflow: hidden;
    }

    .reviews-track {
        display: flex;
        gap: 20px;
        transition: transform 0.5s ease-in-out;
    }

    .carousel-prev,
    .carousel-next {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 10;
        transition: all 0.2s;
    }

    .carousel-prev:hover,
    .carousel-next:hover {
        background: #f3f4f6;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .carousel-prev {
        left: -20px;
    }

    .carousel-next {
        right: -20px;
    }

    .carousel-prev:disabled,
    .carousel-next:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    @media (max-width: 768px) {
        .carousel-prev {
            left: 0;
        }
        .carousel-next {
            right: 0;
        }
    }
`;
document.head.appendChild(style);
