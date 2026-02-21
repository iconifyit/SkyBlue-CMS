/**
 * IconMason Skin - Main JavaScript
 *
 * Custom functionality for the IconMason documentation site.
 */

(function() {
    'use strict';

    /**
     * Highlight the current page in the sidebar navigation.
     * Adds 'active' class to the nav-link matching the current URL.
     */
    function highlightCurrentPage() {
        var currentPath = window.location.pathname;
        var navLinks = document.querySelectorAll('.sidebar .nav-link');

        navLinks.forEach(function(link) {
            var linkPath = link.getAttribute('href');
            if (linkPath && currentPath.indexOf(linkPath.replace('.html', '')) !== -1) {
                link.classList.add('active');
            }
        });
    }

    /**
     * Add smooth scrolling to anchor links.
     */
    function initSmoothScroll() {
        document.querySelectorAll('a[href^="#"]').forEach(function(anchor) {
            anchor.addEventListener('click', function(e) {
                var targetId = this.getAttribute('href');
                if (targetId === '#') {
                    return;
                }

                var target = document.querySelector(targetId);
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({
                        behavior : 'smooth',
                        block    : 'start'
                    });
                }
            });
        });
    }

    /**
     * Add copy button to code blocks.
     */
    function initCodeCopyButtons() {
        var codeBlocks = document.querySelectorAll('.doc-content pre');

        codeBlocks.forEach(function(block) {
            var wrapper = document.createElement('div');
            wrapper.className = 'code-block-wrapper position-relative';
            block.parentNode.insertBefore(wrapper, block);
            wrapper.appendChild(block);

            var copyBtn = document.createElement('button');
            copyBtn.className = 'btn btn-sm btn-outline-secondary code-copy-btn position-absolute';
            copyBtn.innerHTML = '<i class="bi bi-clipboard"></i>';
            copyBtn.title = 'Copy to clipboard';
            copyBtn.style.top = '0.5rem';
            copyBtn.style.right = '0.5rem';
            copyBtn.style.opacity = '0';
            copyBtn.style.transition = 'opacity 0.2s';

            wrapper.appendChild(copyBtn);

            wrapper.addEventListener('mouseenter', function() {
                copyBtn.style.opacity = '1';
            });

            wrapper.addEventListener('mouseleave', function() {
                copyBtn.style.opacity = '0';
            });

            copyBtn.addEventListener('click', function() {
                var code = block.querySelector('code');
                var text = code ? code.textContent : block.textContent;

                navigator.clipboard.writeText(text).then(function() {
                    copyBtn.innerHTML = '<i class="bi bi-check"></i>';
                    setTimeout(function() {
                        copyBtn.innerHTML = '<i class="bi bi-clipboard"></i>';
                    }, 2000);
                });
            });
        });
    }

    /**
     * Handle image loading for documentation screenshots.
     * Adds loading state and error handling.
     */
    function initImageHandling() {
        var images = document.querySelectorAll('.doc-content img');

        images.forEach(function(img) {
            img.addEventListener('load', function() {
                this.classList.add('loaded');
            });

            img.addEventListener('error', function() {
                this.alt = 'Image not available';
                this.classList.add('error');
            });
        });
    }

    /**
     * Collapse sidebar on mobile after clicking a link.
     */
    function initMobileSidebar() {
        var sidebarLinks = document.querySelectorAll('.sidebar .nav-link');

        sidebarLinks.forEach(function(link) {
            link.addEventListener('click', function() {
                if (window.innerWidth < 768) {
                    var sidebar = document.getElementById('sidebarMenu');
                    if (sidebar && sidebar.classList.contains('show')) {
                        sidebar.classList.remove('show');
                    }
                }
            });
        });
    }

    /**
     * Initialize all functionality when DOM is ready.
     */
    function init() {
        highlightCurrentPage();
        initSmoothScroll();
        initCodeCopyButtons();
        initImageHandling();
        initMobileSidebar();
    }

    // Run initialization
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    }
    else {
        init();
    }

})();
