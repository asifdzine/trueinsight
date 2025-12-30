(function () {
    'use strict';

    /**
     * Initialize featured posts carousel with Swiper
     */
    function initFeaturedPostsCarousel() {
        // Wait for Swiper to be available
        if (typeof Swiper === 'undefined') {
            // Retry after a short delay
            setTimeout(initFeaturedPostsCarousel, 100);
            return;
        }

        const carousels = document.querySelectorAll('.featured-posts-swiper');

        carousels.forEach(carousel => {
            const instanceId = carousel.closest('.featured-posts-carousel-wrapper')?.getAttribute('data-instance-id');
            const postsToShow = parseInt(carousel.getAttribute('data-posts-to-show')) || 2;
            const wrapper = carousel.closest('.featured-posts-carousel-wrapper');

            if (!wrapper) return;

            // Check if Swiper is already initialized
            if (carousel.swiper) {
                carousel.swiper.destroy(true, true);
            }

            // Get navigation buttons
            const prevBtn = wrapper.querySelector('.featured-posts-prev');
            const nextBtn = wrapper.querySelector('.featured-posts-next');

            // Initialize Swiper
            const swiper = new Swiper(carousel, {
                slidesPerView: postsToShow,
                spaceBetween: 30,
                navigation: {
                    nextEl: nextBtn,
                    prevEl: prevBtn,
                },
                breakpoints: {
                    320: {
                        slidesPerView: 1,
                        spaceBetween: 20,
                    },
                    768: {
                        slidesPerView: Math.min(2, postsToShow),
                        spaceBetween: 30,
                    },
                    1024: {
                        slidesPerView: postsToShow,
                        spaceBetween: 30,
                    },
                },
                speed: 500,
                grabCursor: true,
            });

            // Store swiper instance
            carousel.swiper = swiper;
        });
    }

    // Initialize on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initFeaturedPostsCarousel);
    } else {
        initFeaturedPostsCarousel();
    }

    // Re-initialize after AJAX content loads (for Bricks builder)
    if (typeof window.bricksData !== 'undefined') {
        document.addEventListener('bricks/ajax/query_result/displayed', function () {
            setTimeout(initFeaturedPostsCarousel, 100);
        });
    }

    // Also initialize when Swiper loads if it wasn't available initially
    window.addEventListener('load', function () {
        if (typeof Swiper !== 'undefined') {
            initFeaturedPostsCarousel();
        }
    });
})();
