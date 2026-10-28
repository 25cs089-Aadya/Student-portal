/**
 * STUDENTHUB PORTAL - CORE JAVASCRIPT
 * Features: Responsive Navigation drawer, A11y attributes toggling, active page highlighting, and form mock handlers.
 */

document.addEventListener('DOMContentLoaded', () => {
    // ----------------------------------------------------
    // 1. MOBILE MENU DRAWER & ARIA TOGGLING
    // ----------------------------------------------------
    const navToggle = document.querySelector('.mobile-nav-toggle');
    const primaryNav = document.querySelector('#primary-navigation');

    if (navToggle && primaryNav) {
        navToggle.addEventListener('click', () => {
            const isOpened = navToggle.getAttribute('aria-expanded') === 'true';
            
            // Toggle classes and accessibility attributes
            if (isOpened) {
                navToggle.setAttribute('aria-expanded', 'false');
                primaryNav.classList.remove('active');
                document.body.style.overflow = ''; // Enable scrolling
            } else {
                navToggle.setAttribute('aria-expanded', 'true');
                primaryNav.classList.add('active');
                document.body.style.overflow = 'hidden'; // Lock screen scroll on mobile drawer
            }
        });

        // Close mobile nav if a click happens outside the drawer area
        document.addEventListener('click', (e) => {
            if (!primaryNav.contains(e.target) && !navToggle.contains(e.target) && primaryNav.classList.contains('active')) {
                navToggle.setAttribute('aria-expanded', 'false');
                primaryNav.classList.remove('active');
                document.body.style.overflow = '';
            }
        });
    }

    // ----------------------------------------------------
    // 2. DYNAMIC ACTIVE PAGE LINK HIGHLIGHTING
    // ----------------------------------------------------
    const navLinks = document.querySelectorAll('.nav-link, .login-btn');
    const currentPath = window.location.pathname;

    navLinks.forEach(link => {
        const linkHref = link.getAttribute('href');
        
        // Extract page name from URL paths
        const pageName = currentPath.substring(currentPath.lastIndexOf('/') + 1);
        
        // Match link hrefs against URL path
        if (linkHref && (pageName === linkHref || (pageName === '' && linkHref.includes('index.html')))) {
            // Remove active status from default link if needed
            navLinks.forEach(l => l.classList.remove('active'));
            link.classList.add('active');
        }
    });

    // ----------------------------------------------------
    // 3. MOCK FORM SUBMISSIONS WITH ACCESSBILITY NOTICES
    // ----------------------------------------------------
    const forms = document.querySelectorAll('.styled-form');

    forms.forEach(form => {
        form.addEventListener('submit', (e) => {
            e.preventDefault(); // Prevent standard page redirects

            const formId = form.getAttribute('id');
            const submitButton = form.querySelector('button[type="submit"]');
            const originalButtonText = submitButton ? submitButton.textContent : 'Submit';

            // Show loading indicator state
            if (submitButton) {
                submitButton.disabled = true;
                submitButton.textContent = 'Processing Request...';
            }

            // Mock network latency of 1 second
            setTimeout(() => {
                // Restore button state
                if (submitButton) {
                    submitButton.disabled = false;
                    submitButton.textContent = originalButtonText;
                }

                // Handle outcomes per form type
                if (formId === 'login-form') {
                    alert('Successfully Logged In! Redirecting to Dashboard...');
                    window.location.href = 'dashboard.html';
                } else if (formId === 'register-form') {
                    alert('Registration Completed Successfully! You can now Log In.');
                    window.location.href = 'login.html';
                } else if (formId === 'contact-form') {
                    alert('Message Sent Successfully! We will respond shortly.');
                    form.reset();
                } else {
                    alert('Action executed successfully.');
                    form.reset();
                }
            }, 1000);
        });
    });
});
