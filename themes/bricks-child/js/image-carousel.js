(function () {
    'use strict';

    /**
     * Initialize image carousel with Swiper
     */
    function initImageCarousel() {
        // Wait for Swiper to be available
        if (typeof Swiper === 'undefined') {
            // Retry after a short delay
            setTimeout(initImageCarousel, 100);
            return;
        }

        const carousels = document.querySelectorAll('.image-carousel-swiper');

        carousels.forEach(carousel => {
            const wrapper = carousel.closest('.image-carousel-wrapper');
            if (!wrapper) return;

            // Check if Swiper is already initialized
            if (carousel.swiper) {
                carousel.swiper.destroy(true, true);
            }

            // Get settings from data attributes
            const autoplay = wrapper.getAttribute('data-autoplay') === 'true';
            const autoplayDelay = parseInt(wrapper.getAttribute('data-autoplay-delay')) || 3000;

            // Get navigation buttons
            const prevBtn = wrapper.querySelector('.image-carousel-prev');
            const nextBtn = wrapper.querySelector('.image-carousel-next');

            // Swiper configuration
            const swiperConfig = {
                slidesPerView: 1, // Default for mobile
                spaceBetween: 20,
                navigation: {
                    nextEl: nextBtn,
                    prevEl: prevBtn,
                },
                breakpoints: {
                    // Mobile: 1 slide
                    320: {
                        slidesPerView: 1,
                        spaceBetween: 20,
                    },
                    // Tablet: 2-3 slides
                    768: {
                        slidesPerView: 2,
                        spaceBetween: 20,
                    },
                    // Desktop: 5 slides
                    1024: {
                        slidesPerView: 5,
                        spaceBetween: 20,
                    },
                },
                speed: 500,
                grabCursor: true,
                loop: false,
            };

            // Add pagination if exists
            const pagination = carousel.querySelector('.swiper-pagination');
            if (pagination) {
                swiperConfig.pagination = {
                    el: pagination,
                    clickable: true,
                };
            }

            // Add autoplay if enabled
            if (autoplay) {
                swiperConfig.autoplay = {
                    delay: autoplayDelay,
                    disableOnInteraction: false,
                };
            }

            // Initialize Swiper
            const swiper = new Swiper(carousel, swiperConfig);

            // Store swiper instance
            carousel.swiper = swiper;
        });
    }

    // Initialize on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initImageCarousel);
    } else {
        initImageCarousel();
    }

    // Re-initialize after AJAX content loads (for Bricks builder)
    if (typeof window.bricksData !== 'undefined') {
        document.addEventListener('bricks/ajax/query_result/displayed', function () {
            setTimeout(initImageCarousel, 100);
        });
    }

    // Also initialize when Swiper loads if it wasn't available initially
    window.addEventListener('load', function () {
        if (typeof Swiper !== 'undefined') {
            initImageCarousel();
        }
    });
})();

