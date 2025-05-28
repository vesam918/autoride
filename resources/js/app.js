/**
 * AutoRide Theme Frontend JavaScript
 */

import './modules/navigation';
import './modules/booking';
import './modules/animations';
import { initializeTheme } from './modules/theme';

// Initialize theme when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    // Theme initialization
    initializeTheme({
        debug: process.env.NODE_ENV === 'development',
        ajaxUrl: window?.autorideData?.ajaxUrl || '',
        nonce: window?.autorideData?.nonce || '',
        isLoggedIn: window?.autorideData?.isLoggedIn || false
    });

    // Initialize components
    initializeComponents();

    // Setup event listeners
    setupEventListeners();
});

/**
 * Initialize all components
 */
function initializeComponents() {
    // Lazy loading images
    const lazyImages = document.querySelectorAll('img[data-src]');
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    if (img.dataset.srcset) {
                        img.srcset = img.dataset.srcset;
                    }
                    img.classList.add('fade-in');
                    observer.unobserve(img);
                }
            });
        });

        lazyImages.forEach(img => imageObserver.observe(img));
    }

    // Initialize modals
    const modals = document.querySelectorAll('[data-modal]');
    modals.forEach(modal => {
        const trigger = document.querySelector(`[data-modal-trigger="${modal.id}"]`);
        if (trigger) {
            trigger.addEventListener('click', (e) => {
                e.preventDefault();
                modal.classList.remove('hidden');
                modal.classList.add('fade-in');
            });

            const closeBtn = modal.querySelector('[data-modal-close]');
            if (closeBtn) {
                closeBtn.addEventListener('click', () => {
                    modal.classList.add('hidden');
                    modal.classList.remove('fade-in');
                });
            }
        }
    });

    // Initialize dropdowns
    const dropdowns = document.querySelectorAll('[data-dropdown]');
    dropdowns.forEach(dropdown => {
        const trigger = dropdown.querySelector('[data-dropdown-trigger]');
        const content = dropdown.querySelector('[data-dropdown-content]');

        if (trigger && content) {
            trigger.addEventListener('click', () => {
                content.classList.toggle('hidden');
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', (e) => {
                if (!dropdown.contains(e.target)) {
                    content.classList.add('hidden');
                }
            });
        }
    });
}

/**
 * Setup global event listeners
 */
function setupEventListeners() {
    // Handle mobile menu
    const mobileMenuButton = document.querySelector('[data-mobile-menu-toggle]');
    const mobileMenu = document.querySelector('[data-mobile-menu]');

    if (mobileMenuButton && mobileMenu) {
        mobileMenuButton.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
            document.body.classList.toggle('overflow-hidden');
        });
    }

    // Handle smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', (e) => {
            e.preventDefault();
            const target = document.querySelector(anchor.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Handle form submissions
    document.querySelectorAll('form[data-ajax]').forEach(form => {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(form);
            
            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-WP-Nonce': window?.autorideData?.nonce || ''
                    }
                });

                const data = await response.json();
                
                if (data.success) {
                    form.reset();
                    showNotification('success', data.message);
                } else {
                    showNotification('error', data.message);
                }
            } catch (error) {
                console.error('Form submission error:', error);
                showNotification('error', 'An error occurred. Please try again.');
            }
        });
    });
}

/**
 * Show notification message
 */
function showNotification(type, message) {
    const notification = document.createElement('div');
    notification.className = `fixed bottom-4 right-4 p-4 rounded-lg shadow-lg ${
        type === 'success' ? 'bg-green-500' : 'bg-red-500'
    } text-white`;
    notification.textContent = message;

    document.body.appendChild(notification);

    setTimeout(() => {
        notification.remove();
    }, 3000);
}

// Export for use in other modules
export { showNotification };
