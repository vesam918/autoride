/**
 * Theme Module
 * Handles core theme functionality and initialization
 */

class Theme {
    constructor(config = {}) {
        // Configuration
        this.config = {
            debug: false,
            ajaxUrl: '',
            nonce: '',
            isLoggedIn: false,
            ...config
        };

        // State
        this.state = {
            isLoading: false,
            isMobile: window.innerWidth < 768,
            darkMode: this.getDarkModePreference(),
            userPreferences: this.loadUserPreferences()
        };

        // Initialize
        this.init();
    }

    init() {
        // Initialize core functionality
        this.setupEventListeners();
        this.initializeThemeFeatures();
        this.handleMediaQueries();
        this.setupAccessibility();
        
        // Log debug information if enabled
        if (this.config.debug) {
            this.logDebugInfo();
        }
    }

    setupEventListeners() {
        // Handle window events
        window.addEventListener('resize', this.debounce(() => {
            this.state.isMobile = window.innerWidth < 768;
            this.emit('themeResize', { isMobile: this.state.isMobile });
        }, 250));

        // Handle theme switching
        const themeToggle = document.querySelector('[data-theme-toggle]');
        if (themeToggle) {
            themeToggle.addEventListener('click', () => this.toggleDarkMode());
        }

        // Handle print events
        window.addEventListener('beforeprint', () => {
            document.body.classList.add('is-printing');
        });

        window.addEventListener('afterprint', () => {
            document.body.classList.remove('is-printing');
        });
    }

    initializeThemeFeatures() {
        // Initialize lazy loading
        this.initializeLazyLoading();

        // Initialize tooltips
        this.initializeTooltips();

        // Initialize copy functionality
        this.initializeCopyButtons();

        // Initialize external links
        this.handleExternalLinks();

        // Initialize scroll restoration
        this.initializeScrollRestoration();
    }

    initializeLazyLoading() {
        const lazyElements = document.querySelectorAll('[data-lazy]');
        
        if ('IntersectionObserver' in window) {
            const lazyObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        this.loadLazyElement(entry.target);
                        lazyObserver.unobserve(entry.target);
                    }
                });
            });

            lazyElements.forEach(element => lazyObserver.observe(element));
        } else {
            // Fallback for older browsers
            lazyElements.forEach(element => this.loadLazyElement(element));
        }
    }

    initializeTooltips() {
        const tooltips = document.querySelectorAll('[data-tooltip]');
        
        tooltips.forEach(element => {
            const tooltip = document.createElement('div');
            tooltip.className = 'tooltip';
            tooltip.textContent = element.dataset.tooltip;
            
            element.addEventListener('mouseenter', () => {
                document.body.appendChild(tooltip);
                const rect = element.getBoundingClientRect();
                tooltip.style.top = `${rect.top - tooltip.offsetHeight - 10}px`;
                tooltip.style.left = `${rect.left + (rect.width - tooltip.offsetWidth) / 2}px`;
            });

            element.addEventListener('mouseleave', () => {
                tooltip.remove();
            });
        });
    }

    initializeCopyButtons() {
        const copyButtons = document.querySelectorAll('[data-copy]');
        
        copyButtons.forEach(button => {
            button.addEventListener('click', async () => {
                const text = button.dataset.copy;
                try {
                    await navigator.clipboard.writeText(text);
                    this.showNotification('success', 'Copied to clipboard!');
                } catch (err) {
                    this.showNotification('error', 'Failed to copy text');
                }
            });
        });
    }

    handleExternalLinks() {
        const links = document.querySelectorAll('a[href^="http"]');
        
        links.forEach(link => {
            if (link.hostname !== window.location.hostname) {
                link.setAttribute('rel', 'noopener noreferrer');
                link.setAttribute('target', '_blank');
            }
        });
    }

    initializeScrollRestoration() {
        if ('scrollRestoration' in history) {
            history.scrollRestoration = 'manual';
        }

        // Store scroll position before unload
        window.addEventListener('beforeunload', () => {
            localStorage.setItem('scrollPosition', window.scrollY);
        });

        // Restore scroll position
        window.addEventListener('load', () => {
            const scrollPosition = localStorage.getItem('scrollPosition');
            if (scrollPosition) {
                window.scrollTo(0, parseInt(scrollPosition));
                localStorage.removeItem('scrollPosition');
            }
        });
    }

    handleMediaQueries() {
        // Watch for dark mode changes
        const darkModeMediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
        darkModeMediaQuery.addListener((e) => {
            if (this.state.userPreferences.respectSystemTheme) {
                this.setDarkMode(e.matches);
            }
        });

        // Watch for reduced motion preference
        const motionMediaQuery = window.matchMedia('(prefers-reduced-motion: reduce)');
        motionMediaQuery.addListener((e) => {
            document.body.classList.toggle('reduce-motion', e.matches);
        });
    }

    setupAccessibility() {
        // Skip to content link
        const skipLink = document.querySelector('.skip-to-content');
        if (skipLink) {
            skipLink.addEventListener('click', (e) => {
                e.preventDefault();
                const target = document.querySelector(skipLink.hash);
                target?.focus();
            });
        }

        // Focus trap for modals
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Tab') {
                const modal = document.querySelector('.modal.is-active');
                if (modal) {
                    this.handleModalFocus(e, modal);
                }
            }
        });
    }

    // Utility Methods
    loadLazyElement(element) {
        if (element.dataset.src) {
            element.src = element.dataset.src;
        }
        if (element.dataset.srcset) {
            element.srcset = element.dataset.srcset;
        }
        if (element.dataset.background) {
            element.style.backgroundImage = `url('${element.dataset.background}')`;
        }
    }

    toggleDarkMode() {
        this.setDarkMode(!this.state.darkMode);
        this.saveUserPreferences();
    }

    setDarkMode(enabled) {
        this.state.darkMode = enabled;
        document.documentElement.classList.toggle('dark', enabled);
        this.emit('themeChange', { darkMode: enabled });
    }

    getDarkModePreference() {
        const saved = localStorage.getItem('darkMode');
        if (saved !== null) {
            return JSON.parse(saved);
        }
        return window.matchMedia('(prefers-color-scheme: dark)').matches;
    }

    loadUserPreferences() {
        const saved = localStorage.getItem('userPreferences');
        return saved ? JSON.parse(saved) : {
            respectSystemTheme: true,
            fontSize: 'medium',
            reduceMotion: false
        };
    }

    saveUserPreferences() {
        localStorage.setItem('userPreferences', JSON.stringify(this.state.userPreferences));
        localStorage.setItem('darkMode', JSON.stringify(this.state.darkMode));
    }

    showNotification(type, message) {
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.textContent = message;
        
        document.body.appendChild(notification);
        setTimeout(() => notification.remove(), 3000);
    }

    debounce(func, wait) {
        let timeout;
        return (...args) => {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), wait);
        };
    }

    emit(eventName, detail) {
        const event = new CustomEvent(eventName, { detail });
        window.dispatchEvent(event);
    }

    handleModalFocus(event, modal) {
        const focusableElements = modal.querySelectorAll(
            'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
        );
        const firstFocusable = focusableElements[0];
        const lastFocusable = focusableElements[focusableElements.length - 1];

        if (event.shiftKey) {
            if (document.activeElement === firstFocusable) {
                lastFocusable.focus();
                event.preventDefault();
            }
        } else {
            if (document.activeElement === lastFocusable) {
                firstFocusable.focus();
                event.preventDefault();
            }
        }
    }

    logDebugInfo() {
        console.log('Theme Debug Information:', {
            config: this.config,
            state: this.state,
            userAgent: navigator.userAgent,
            viewport: {
                width: window.innerWidth,
                height: window.innerHeight
            }
        });
    }
}

// Export initialization function
export function initializeTheme(config) {
    return new Theme(config);
}

export default Theme;
