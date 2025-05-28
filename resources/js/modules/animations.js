/**
 * Animations Module
 * Handles all animation-related functionality using modern techniques
 */

class Animations {
    constructor() {
        // Configuration
        this.config = {
            threshold: 0.2,
            rootMargin: '0px',
            animationDelay: 50
        };

        // Animation classes
        this.animationClasses = {
            fadeIn: 'animate-fade-in',
            slideUp: 'animate-slide-up',
            slideDown: 'animate-slide-down',
            slideLeft: 'animate-slide-left',
            slideRight: 'animate-slide-right',
            scale: 'animate-scale',
            rotate: 'animate-rotate'
        };

        // Initialize
        this.init();
    }

    init() {
        // Setup intersection observer for scroll animations
        this.setupScrollAnimations();

        // Initialize hover animations
        this.setupHoverAnimations();

        // Initialize parallax effects
        this.setupParallax();

        // Initialize counter animations
        this.setupCounters();

        // Initialize loading animations
        this.setupLoadingAnimations();
    }

    setupScrollAnimations() {
        const animatedElements = document.querySelectorAll('[data-animate]');

        if (!animatedElements.length) return;

        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    this.animateElement(entry.target);
                    observer.unobserve(entry.target);
                }
            });
        }, this.config);

        animatedElements.forEach((element, index) => {
            // Set initial state
            element.style.opacity = '0';
            element.style.transform = this.getInitialTransform(element.dataset.animate);
            
            // Add delay if specified
            if (element.dataset.delay) {
                element.style.transitionDelay = `${element.dataset.delay}ms`;
            } else {
                element.style.transitionDelay = `${index * this.config.animationDelay}ms`;
            }

            observer.observe(element);
        });
    }

    setupHoverAnimations() {
        const hoverElements = document.querySelectorAll('[data-hover]');

        hoverElements.forEach(element => {
            element.addEventListener('mouseenter', () => {
                const animation = element.dataset.hover;
                element.classList.add(this.animationClasses[animation]);
            });

            element.addEventListener('mouseleave', () => {
                const animation = element.dataset.hover;
                element.classList.remove(this.animationClasses[animation]);
            });
        });
    }

    setupParallax() {
        const parallaxElements = document.querySelectorAll('[data-parallax]');

        if (!parallaxElements.length) return;

        window.addEventListener('scroll', () => {
            parallaxElements.forEach(element => {
                const speed = element.dataset.parallax || 0.5;
                const rect = element.getBoundingClientRect();
                const scrolled = window.pageYOffset;

                if (rect.top < window.innerHeight && rect.bottom > 0) {
                    const yPos = -(scrolled * speed);
                    element.style.transform = `translate3d(0, ${yPos}px, 0)`;
                }
            });
        }, {
            passive: true
        });
    }

    setupCounters() {
        const counters = document.querySelectorAll('[data-counter]');

        if (!counters.length) return;

        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    this.animateCounter(entry.target);
                    observer.unobserve(entry.target);
                }
            });
        }, this.config);

        counters.forEach(counter => {
            observer.observe(counter);
        });
    }

    setupLoadingAnimations() {
        window.addEventListener('load', () => {
            document.body.classList.add('loaded');
            
            // Animate hero section elements
            const heroElements = document.querySelectorAll('.hero [data-animate]');
            heroElements.forEach((element, index) => {
                setTimeout(() => {
                    this.animateElement(element);
                }, index * 200);
            });
        });
    }

    animateElement(element) {
        const animation = element.dataset.animate;
        
        // Remove initial transform and show element
        element.style.transform = 'none';
        element.style.opacity = '1';

        // Add animation class if specified
        if (this.animationClasses[animation]) {
            element.classList.add(this.animationClasses[animation]);
        }
    }

    animateCounter(element) {
        const target = parseInt(element.dataset.counter) || 0;
        const duration = parseInt(element.dataset.duration) || 2000;
        const increment = target / (duration / 16); // 60fps
        let current = 0;

        const updateCounter = () => {
            current += increment;
            if (current >= target) {
                element.textContent = target.toLocaleString();
            } else {
                element.textContent = Math.floor(current).toLocaleString();
                requestAnimationFrame(updateCounter);
            }
        };

        requestAnimationFrame(updateCounter);
    }

    getInitialTransform(animation) {
        switch (animation) {
            case 'slideUp':
                return 'translate3d(0, 50px, 0)';
            case 'slideDown':
                return 'translate3d(0, -50px, 0)';
            case 'slideLeft':
                return 'translate3d(50px, 0, 0)';
            case 'slideRight':
                return 'translate3d(-50px, 0, 0)';
            case 'scale':
                return 'scale(0.8)';
            case 'rotate':
                return 'rotate(-15deg)';
            default:
                return 'none';
        }
    }

    // Utility method to add animation to element programmatically
    static animate(element, animation, options = {}) {
        const instance = new Animations();
        element.dataset.animate = animation;
        
        if (options.delay) {
            element.dataset.delay = options.delay;
        }
        
        instance.animateElement(element);
    }
}

// Initialize animations
document.addEventListener('DOMContentLoaded', () => {
    new Animations();
});

export default Animations;
