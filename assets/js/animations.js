/**
 * 🎬 PREMIUM ANIMATIONS & INTERACTIONS
 * Advanced JavaScript for smooth user experience
 * Features: Scroll Animations, Ripple Effects, Count-up, Parallax
 */

// ============================================
// 🎯 INITIALIZATION
// ============================================
document.addEventListener('DOMContentLoaded', function() {
    initScrollAnimations();
    initRippleEffects();
    initCountUpAnimations();
    initSmoothScroll();
    initActivePageDetection();
    initMobileMenu();
    initParallaxEffects();
    initCardHoverEffects();
});

// ============================================
// 📜 SCROLL ANIMATIONS - Intersection Observer
// ============================================
function initScrollAnimations() {
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-fade-in-up');
                entry.target.style.opacity = '1';
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    // Observe all elements with data-animate attribute
    document.querySelectorAll('[data-animate]').forEach(el => {
        el.style.opacity = '0';
        observer.observe(el);
    });

    // Observe cards for staggered animation
    document.querySelectorAll('.stat-card, .card-premium').forEach((card, index) => {
        card.style.opacity = '0';
        card.style.animationDelay = `${index * 0.1}s`;
        observer.observe(card);
    });
}

// ============================================
// 💧 RIPPLE EFFECT - Material Design Style
// ============================================
function initRippleEffects() {
    document.querySelectorAll('.btn-premium, button, .ripple').forEach(button => {
        button.addEventListener('click', function(e) {
            const ripple = document.createElement('span');
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;

            ripple.style.width = ripple.style.height = size + 'px';
            ripple.style.left = x + 'px';
            ripple.style.top = y + 'px';
            ripple.classList.add('ripple-effect');

            this.appendChild(ripple);

            setTimeout(() => ripple.remove(), 600);
        });
    });
}

// Add ripple CSS dynamically
const rippleStyle = document.createElement('style');
rippleStyle.textContent = `
    .ripple-effect {
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.6);
        transform: scale(0);
        animation: ripple-animation 0.6s ease-out;
        pointer-events: none;
    }
    
    @keyframes ripple-animation {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }
    
    button, .btn-premium, .ripple {
        position: relative;
        overflow: hidden;
    }
`;
document.head.appendChild(rippleStyle);

// ============================================
// 🔢 COUNT-UP ANIMATION - For Statistics
// ============================================
function initCountUpAnimations() {
    const countElements = document.querySelectorAll('[data-count]');
    
    const observerOptions = {
        threshold: 0.5
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const element = entry.target;
                const target = parseInt(element.getAttribute('data-count'));
                animateCount(element, target);
                observer.unobserve(element);
            }
        });
    }, observerOptions);

    countElements.forEach(el => observer.observe(el));
}

function animateCount(element, target) {
    let current = 0;
    const increment = target / 50;
    const duration = 1500;
    const stepTime = duration / 50;

    const timer = setInterval(() => {
        current += increment;
        if (current >= target) {
            element.textContent = target;
            clearInterval(timer);
        } else {
            element.textContent = Math.floor(current);
        }
    }, stepTime);
}

// ============================================
// 🎯 SMOOTH SCROLL - Enhanced Navigation
// ============================================
function initSmoothScroll() {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            if (href === '#') return;
            
            e.preventDefault();
            const target = document.querySelector(href);
            
            if (target) {
                const offsetTop = target.offsetTop - 80; // Account for fixed navbar
                window.scrollTo({
                    top: offsetTop,
                    behavior: 'smooth'
                });
            }
        });
    });
}

// ============================================
// 📍 ACTIVE PAGE DETECTION - Navbar Highlight
// ============================================
function initActivePageDetection() {
    const currentPath = window.location.pathname;
    const navLinks = document.querySelectorAll('nav a');

    navLinks.forEach(link => {
        const linkPath = new URL(link.href).pathname;
        
        if (currentPath === linkPath || currentPath.includes(linkPath)) {
            link.classList.add('active-page');
            
            // Add active indicator
            if (!link.querySelector('.active-indicator')) {
                const indicator = document.createElement('span');
                indicator.className = 'active-indicator';
                link.appendChild(indicator);
            }
        }
    });
}

// Add active page styles
const activePageStyle = document.createElement('style');
activePageStyle.textContent = `
    .active-page {
        position: relative;
        background: rgba(255, 255, 255, 0.1) !important;
        font-weight: 600;
    }
    
    .active-indicator {
        position: absolute;
        bottom: -2px;
        left: 50%;
        transform: translateX(-50%);
        width: 60%;
        height: 3px;
        background: var(--accent);
        border-radius: 2px 2px 0 0;
        animation: slideIn 0.3s ease-out;
    }
    
    @keyframes slideIn {
        from {
            width: 0;
        }
        to {
            width: 60%;
        }
    }
`;
document.head.appendChild(activePageStyle);

// ============================================
// 🍔 MOBILE MENU - Animated Hamburger
// ============================================
function initMobileMenu() {
    const menuBtn = document.getElementById('mobile-menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');
    
    if (menuBtn && mobileMenu) {
        menuBtn.addEventListener('click', function() {
            const isOpen = !mobileMenu.classList.contains('hidden');
            
            // Toggle menu
            mobileMenu.classList.toggle('hidden');
            
            // Animate hamburger icon
            this.classList.toggle('active');
            
            // Add slide animation
            if (!isOpen) {
                mobileMenu.classList.add('animate-slide-in-right');
            }
        });
        
        // Close menu when clicking outside
        document.addEventListener('click', function(e) {
            if (!menuBtn.contains(e.target) && !mobileMenu.contains(e.target)) {
                mobileMenu.classList.add('hidden');
                menuBtn.classList.remove('active');
            }
        });
    }
}

// Hamburger animation styles
const hamburgerStyle = document.createElement('style');
hamburgerStyle.textContent = `
    #mobile-menu-btn {
        transition: all 0.3s ease;
    }
    
    #mobile-menu-btn.active {
        transform: rotate(90deg);
    }
    
    #mobile-menu {
        animation: slideInRight 0.3s ease-out;
    }
`;
document.head.appendChild(hamburgerStyle);

// ============================================
// 🌊 PARALLAX EFFECTS - Subtle Depth
// ============================================
function initParallaxEffects() {
    const parallaxElements = document.querySelectorAll('[data-parallax]');
    
    if (parallaxElements.length === 0) return;
    
    window.addEventListener('scroll', () => {
        const scrolled = window.pageYOffset;
        
        parallaxElements.forEach(el => {
            const speed = el.getAttribute('data-parallax') || 0.5;
            const yPos = -(scrolled * speed);
            el.style.transform = `translateY(${yPos}px)`;
        });
    });
}

// ============================================
// 🎴 CARD HOVER EFFECTS - Enhanced Interactions
// ============================================
function initCardHoverEffects() {
    const cards = document.querySelectorAll('.card-premium, .stat-card');
    
    cards.forEach(card => {
        card.addEventListener('mouseenter', function(e) {
            this.style.transform = 'translateY(-8px) scale(1.02)';
        });
        
        card.addEventListener('mouseleave', function(e) {
            this.style.transform = 'translateY(0) scale(1)';
        });
        
        // 3D tilt effect on mouse move
        card.addEventListener('mousemove', function(e) {
            const rect = this.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            
            const centerX = rect.width / 2;
            const centerY = rect.height / 2;
            
            const rotateX = (y - centerY) / 20;
            const rotateY = (centerX - x) / 20;
            
            this.style.transform = `
                translateY(-8px) 
                scale(1.02) 
                perspective(1000px) 
                rotateX(${rotateX}deg) 
                rotateY(${rotateY}deg)
            `;
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1) perspective(1000px) rotateX(0) rotateY(0)';
        });
    });
}

// ============================================
// 🎨 GRADIENT ANIMATION - Hero Background
// ============================================
function initGradientAnimation() {
    const heroSection = document.querySelector('.bg-gradient-hero');
    
    if (heroSection) {
        let angle = 0;
        setInterval(() => {
            angle = (angle + 1) % 360;
            heroSection.style.background = `
                linear-gradient(${angle}deg, 
                    #1e3a8a 0%, 
                    #3b82f6 50%, 
                    #1e3a8a 100%)
            `;
        }, 50);
    }
}

// ============================================
// ⭐ RATING STARS - Animated Hover
// ============================================
function initRatingStars() {
    const starContainers = document.querySelectorAll('.rating-stars');
    
    starContainers.forEach(container => {
        const stars = container.querySelectorAll('.star');
        
        stars.forEach((star, index) => {
            star.addEventListener('mouseenter', function() {
                stars.forEach((s, i) => {
                    if (i <= index) {
                        s.classList.add('star-active');
                    } else {
                        s.classList.remove('star-active');
                    }
                });
            });
        });
        
        container.addEventListener('mouseleave', function() {
            stars.forEach(s => s.classList.remove('star-active'));
        });
    });
}

// ============================================
// 🔔 TOAST NOTIFICATIONS - Enhanced
// ============================================
function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `toast toast-${type} animate-slide-in-right`;
    toast.textContent = message;
    
    const toastStyles = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 1rem 1.5rem;
        background: white;
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-xl);
        z-index: 9999;
        max-width: 300px;
    `;
    
    toast.style.cssText = toastStyles;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.style.animation = 'slideOutRight 0.3s ease-out';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

// ============================================
// 🎯 LAZY LOADING - Images
// ============================================
function initLazyLoading() {
    const images = document.querySelectorAll('img[data-src]');
    
    const imageObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.getAttribute('data-src');
                img.removeAttribute('data-src');
                imageObserver.unobserve(img);
            }
        });
    });
    
    images.forEach(img => imageObserver.observe(img));
}

// ============================================
// 🎪 MODAL MANAGEMENT - With Blur Backdrop
// ============================================
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('animate-fade-in');
        document.body.style.overflow = 'hidden';
        
        // Add blur to background
        const backdrop = document.createElement('div');
        backdrop.className = 'modal-backdrop';
        backdrop.style.cssText = `
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(5px);
            z-index: 40;
        `;
        backdrop.onclick = () => closeModal(modalId);
        document.body.appendChild(backdrop);
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    const backdrop = document.querySelector('.modal-backdrop');
    
    if (modal) {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }
    
    if (backdrop) {
        backdrop.remove();
    }
}

// ============================================
// 🎨 THEME UTILITIES
// ============================================
function applyTheme(theme) {
    document.documentElement.setAttribute('data-theme', theme);
    localStorage.setItem('theme', theme);
}

// ============================================
// 📊 PROGRESS BARS - Animated
// ============================================
function animateProgressBar(element, targetPercent) {
    let current = 0;
    const increment = targetPercent / 50;
    
    const timer = setInterval(() => {
        current += increment;
        if (current >= targetPercent) {
            element.style.width = targetPercent + '%';
            clearInterval(timer);
        } else {
            element.style.width = current + '%';
        }
    }, 20);
}

// ============================================
// 🎯 EXPORT FUNCTIONS
// ============================================
window.showToast = showToast;
window.openModal = openModal;
window.closeModal = closeModal;
window.animateProgressBar = animateProgressBar;
window.applyTheme = applyTheme;

// ============================================
// 🎬 PERFORMANCE OPTIMIZATION
// ============================================
// Debounce function for scroll events
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Throttle function for resize events
function throttle(func, limit) {
    let inThrottle;
    return function(...args) {
        if (!inThrottle) {
            func.apply(this, args);
            inThrottle = true;
            setTimeout(() => inThrottle = false, limit);
        }
    };
}

console.log('🎨 Premium Animations Loaded Successfully!');
