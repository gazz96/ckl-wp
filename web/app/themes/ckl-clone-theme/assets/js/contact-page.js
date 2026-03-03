/**
 * Contact Page JavaScript
 *
 * @package CKL_Car_Rental
 */

(function() {
    'use strict';

    /**
     * Initialize contact form enhancements
     */
    function initContactForm() {
        const contactForm = document.getElementById('contact-form');

        if (!contactForm) {
            return;
        }

        // Form submission handler
        contactForm.addEventListener('submit', function(e) {
            // Honeypot check (spam protection)
            const honeypot = document.getElementById('website');
            if (honeypot && honeypot.value !== '') {
                e.preventDefault();
                return false;
            }

            // Validate subject field
            const subject = document.getElementById('subject');
            if (subject && subject.value === '') {
                e.preventDefault();
                subject.focus();
                alert('Please select a subject');
                return false;
            }

            // Validate message length
            const message = document.getElementById('message');
            if (message && message.value.length < 10) {
                e.preventDefault();
                message.focus();
                alert('Message must be at least 10 characters long');
                return false;
            }

            // Add loading state to submit button
            const submitBtn = contactForm.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = `
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Sending...
                `;
            }

            return true;
        });

        // Real-time email validation
        const emailInput = document.getElementById('email');
        if (emailInput) {
            emailInput.addEventListener('blur', function() {
                const email = this.value;
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                if (email && !emailRegex.test(email)) {
                    this.setCustomValidity('Please enter a valid email address');
                    this.classList.add('border-red-500');
                } else {
                    this.setCustomValidity('');
                    this.classList.remove('border-red-500');
                }
            });

            emailInput.addEventListener('input', function() {
                if (this.classList.contains('border-red-500')) {
                    this.setCustomValidity('');
                    this.classList.remove('border-red-500');
                }
            });
        }

        // Phone number formatting (Malaysian format: +60 XX-XXX XXXX)
        const phoneInput = document.getElementById('phone');
        if (phoneInput) {
            phoneInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');

                // Add +60 prefix if not present
                if (value.length > 0 && !value.startsWith('60')) {
                    value = '60' + value;
                }

                // Format: +60 XX-XXX XXXX
                if (value.length > 2) {
                    value = '+' + value;
                }
                if (value.length > 5) {
                    value = value.slice(0, 5) + ' ' + value.slice(5);
                }
                if (value.length > 9) {
                    value = value.slice(0, 9) + ' ' + value.slice(9);
                }
                if (value.length > 13) {
                    value = value.slice(0, 13);
                }

                e.target.value = value;
            });

            phoneInput.addEventListener('blur', function() {
                const phone = this.value;
                const phoneRegex = /^\+60\s?\d{2}-?\d{3,4}\s?\d{3,4}$/;

                if (phone && !phoneRegex.test(phone)) {
                    this.classList.add('border-yellow-500');
                } else {
                    this.classList.remove('border-yellow-500');
                }
            });
        }

        // Character counter for message
        const messageInput = document.getElementById('message');
        const charCount = document.getElementById('char-count');

        if (messageInput && charCount) {
            messageInput.addEventListener('input', function() {
                const count = this.value.length;
                charCount.textContent = count;

                if (count > 500) {
                    charCount.classList.add('text-red-500');
                    this.value = this.value.slice(0, 500);
                    charCount.textContent = 500;
                } else if (count < 10) {
                    charCount.classList.add('text-red-500');
                } else {
                    charCount.classList.remove('text-red-500');
                }
            });

            // Initialize counter
            charCount.textContent = messageInput.value.length;
        }

        // Subject field validation
        const subjectSelect = document.getElementById('subject');
        if (subjectSelect) {
            subjectSelect.addEventListener('change', function() {
                if (this.value === '') {
                    this.classList.add('border-red-500');
                } else {
                    this.classList.remove('border-red-500');
                }
            });
        }
    }

    /**
     * Initialize contact card hover effects
     */
    function initContactCards() {
        const cards = document.querySelectorAll('.contact-card');

        cards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'scale(1.02)';
            });

            card.addEventListener('mouseleave', function() {
                this.style.transform = 'scale(1)';
            });
        });
    }

    /**
     * Initialize social link accessibility
     */
    function initSocialLinks() {
        const socialLinks = document.querySelectorAll('.social-link');

        socialLinks.forEach(link => {
            link.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    this.click();
                }
            });
        });
    }

    /**
     * Initialize all features when DOM is ready
     */
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            initContactForm();
            initContactCards();
            initSocialLinks();
        });
    } else {
        initContactForm();
        initContactCards();
        initSocialLinks();
    }

})();
