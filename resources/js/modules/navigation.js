/**
 * Navigation Module
 * Handles all navigation-related functionality
 */

class Navigation {
    constructor() {
        // Elements
        this.header = document.querySelector('.site-header');
        this.nav = document.querySelector('.main-navigation');
        this.mobileToggle = document.querySelector('.mobile-menu-toggle');
        this.mobileMenu = document.querySelector('.mobile-menu');
        this.dropdowns = document.querySelectorAll('.has-dropdown');
        this.searchToggle = document.querySelector('.search-toggle');
        this.searchForm = document.querySelector('.search-form');

        // State
        this.isSticky = false;
        this.lastScroll = 0;
        this.mobileBreakpoint = 768;

        // Initialize
        this.init();
    }

    init() {
        if (!this.nav) return;

        // Setup event listeners
        this.setupEventListeners();

        // Initialize sticky header
        this.initializeStickyHeader();

        // Initialize mobile menu
        this.initializeMobileMenu();

        // Initialize dropdowns
        this.initializeDropdowns();

        // Initialize search
        this.initializeSearch();

        // Handle resize events
        this.handleResize();
    }

    setupEventListeners() {
        // Scroll events for sticky header
        window.addEventListener('scroll', this.handleScroll.bind(this));

        // Resize events
        window.addEventListener('resize', this.handleResize.bind(this));

        // Escape key handler
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.closeAllMenus();
            }
        });

        // Click outside handler
        document.addEventListener('click', (e) => {
            if (!this.nav.contains(e.target)) {
                this.closeAllMenus();
            }
        });
    }

    initializeStickyHeader() {
        if (!this.header) return;

        const observer = new IntersectionObserver(
            ([entry]) => {
                if (!entry.isIntersecting && window.scrollY > 100) {
                    this.header.classList.add('is-sticky');
                    this.isSticky = true;
                } else {
                    this.header.classList.remove('is-sticky');
                    this.isSticky = false;
                }
            },
            { threshold: [0], rootMargin: '-100px 0px 0px 0px' }
        );

        observer.observe(this.header);
    }

    initializeMobileMenu() {
        if (!this.mobileToggle || !this.mobileMenu) return;

        this.mobileToggle.addEventListener('click', () => {
            const isExpanded = this.mobileToggle.getAttribute('aria-expanded') === 'true';
            this.mobileToggle.setAttribute('aria-expanded', !isExpanded);
            this.mobileMenu.classList.toggle('is-active');
            document.body.classList.toggle('mobile-menu-open');

            // Handle animation
            if (!isExpanded) {
                this.mobileMenu.style.display = 'block';
                requestAnimationFrame(() => {
                    this.mobileMenu.classList.add('menu-visible');
                });
            } else {
                this.mobileMenu.classList.remove('menu-visible');
                this.mobileMenu.addEventListener('transitionend', () => {
                    if (!this.mobileMenu.classList.contains('menu-visible')) {
                        this.mobileMenu.style.display = 'none';
                    }
                }, { once: true });
            }
        });
    }

    initializeDropdowns() {
        this.dropdowns.forEach(dropdown => {
            const trigger = dropdown.querySelector('.dropdown-trigger');
            const menu = dropdown.querySelector('.dropdown-menu');

            if (!trigger || !menu) return;

            // Handle mouse interactions
            dropdown.addEventListener('mouseenter', () => {
                if (window.innerWidth >= this.mobileBreakpoint) {
                    this.openDropdown(dropdown);
                }
            });

            dropdown.addEventListener('mouseleave', () => {
                if (window.innerWidth >= this.mobileBreakpoint) {
                    this.closeDropdown(dropdown);
                }
            });

            // Handle click interactions (mobile)
            trigger.addEventListener('click', (e) => {
                if (window.innerWidth < this.mobileBreakpoint) {
                    e.preventDefault();
                    this.toggleDropdown(dropdown);
                }
            });

            // Handle focus
            trigger.addEventListener('focus', () => {
                if (window.innerWidth >= this.mobileBreakpoint) {
                    this.openDropdown(dropdown);
                }
            });
        });
    }

    initializeSearch() {
        if (!this.searchToggle || !this.searchForm) return;

        this.searchToggle.addEventListener('click', (e) => {
            e.preventDefault();
            this.toggleSearch();
        });
    }

    handleScroll() {
        if (!this.header || !this.isSticky) return;

        const currentScroll = window.scrollY;

        // Show/hide header based on scroll direction
        if (currentScroll > this.lastScroll && currentScroll > 100) {
            // Scrolling down
            this.header.classList.add('header-hidden');
        } else {
            // Scrolling up
            this.header.classList.remove('header-hidden');
        }

        this.lastScroll = currentScroll;
    }

    handleResize() {
        const isMobile = window.innerWidth < this.mobileBreakpoint;

        // Reset mobile menu state on desktop
        if (!isMobile && this.mobileMenu?.classList.contains('is-active')) {
            this.closeMobileMenu();
        }

        // Reset dropdowns on mobile
        if (isMobile) {
            this.dropdowns.forEach(dropdown => {
                this.closeDropdown(dropdown);
            });
        }
    }

    openDropdown(dropdown) {
        const menu = dropdown.querySelector('.dropdown-menu');
        const trigger = dropdown.querySelector('.dropdown-trigger');

        dropdown.classList.add('is-active');
        menu.classList.add('is-active');
        trigger.setAttribute('aria-expanded', 'true');
    }

    closeDropdown(dropdown) {
        const menu = dropdown.querySelector('.dropdown-menu');
        const trigger = dropdown.querySelector('.dropdown-trigger');

        dropdown.classList.remove('is-active');
        menu.classList.remove('is-active');
        trigger.setAttribute('aria-expanded', 'false');
    }

    toggleDropdown(dropdown) {
        const isActive = dropdown.classList.contains('is-active');
        
        // Close other dropdowns
        this.dropdowns.forEach(d => {
            if (d !== dropdown) {
                this.closeDropdown(d);
            }
        });

        // Toggle current dropdown
        if (isActive) {
            this.closeDropdown(dropdown);
        } else {
            this.openDropdown(dropdown);
        }
    }

    toggleSearch() {
        const isVisible = this.searchForm.classList.contains('is-active');

        if (isVisible) {
            this.searchForm.classList.remove('is-active');
            this.searchToggle.setAttribute('aria-expanded', 'false');
        } else {
            this.searchForm.classList.add('is-active');
            this.searchToggle.setAttribute('aria-expanded', 'true');
            this.searchForm.querySelector('input').focus();
        }
    }

    closeAllMenus() {
        // Close dropdowns
        this.dropdowns.forEach(dropdown => {
            this.closeDropdown(dropdown);
        });

        // Close mobile menu
        this.closeMobileMenu();

        // Close search
        if (this.searchForm?.classList.contains('is-active')) {
            this.toggleSearch();
        }
    }

    closeMobileMenu() {
        if (!this.mobileMenu?.classList.contains('is-active')) return;

        this.mobileMenu.classList.remove('is-active');
        this.mobileToggle.setAttribute('aria-expanded', 'false');
        document.body.classList.remove('mobile-menu-open');
    }
}

// Initialize navigation
document.addEventListener('DOMContentLoaded', () => {
    new Navigation();
});

export default Navigation;
