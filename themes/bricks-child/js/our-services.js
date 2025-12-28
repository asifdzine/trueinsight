/**
 * Our Services Element - Image Switching Script
 */
(function () {
  'use strict';

  function initOurServices() {
    // Find all our-services elements
    const serviceElements = document.querySelectorAll('.our-services-wrapper[data-instance-id]');

    if (!serviceElements.length) {
      return;
    }

    serviceElements.forEach(function (wrapper) {
      const serviceItems = wrapper.querySelectorAll('.service-item');
      const mainImage = wrapper.querySelector('.service-main-image'); // Desktop image
      const imageWrapper = wrapper.querySelector('.service-image-wrapper');
      const descriptionContainer = wrapper.querySelector('.service-description');

      if (!serviceItems.length) {
        return;
      }

      // Function to check if mobile
      function isMobile() {
        return window.innerWidth <= 767;
      }

      // Function to handle image switching and description
      function switchImage(item, imageUrl, serviceIndex) {
        // Remove active class from all items
        serviceItems.forEach(function (i) {
          i.classList.remove('active');
        });

        // Add active class to clicked item
        item.classList.add('active');

        // Handle description
        const descriptionHtml = item.getAttribute('data-description-html');
        if (descriptionContainer && imageWrapper) {
          if (descriptionHtml) {
            // Decode the base64 encoded HTML
            try {
              const decodedDescription = atob(descriptionHtml);
              // Update description content
              const descriptionContent = descriptionContainer.querySelector('.service-description-content');
              if (descriptionContent) {
                descriptionContent.innerHTML = decodedDescription;
              }
              // Show description
              descriptionContainer.classList.add('active');
              descriptionContainer.setAttribute('data-service-index', serviceIndex);
            } catch (e) {
              console.error('Error decoding description:', e);
            }
          } else {
            // Hide description if no description for this service
            descriptionContainer.classList.remove('active');
          }
        }

        if (isMobile()) {
          // Mobile: Hide all mobile images
          const allMobileImages = wrapper.querySelectorAll('.service-image-mobile');
          allMobileImages.forEach(function (img) {
            img.classList.remove('active');
            img.style.display = 'none';
          });

          // Show the mobile image for the active service (it's already in the DOM after the service item)
          const mobileImageContainer = wrapper.querySelector('.service-image-mobile[data-service-index="' + serviceIndex + '"]');
          if (mobileImageContainer) {
            mobileImageContainer.classList.add('active');
            mobileImageContainer.style.display = 'block';
          }
        } else {
          // Desktop: Update desktop image
          if (mainImage && imageUrl) {
            mainImage.style.opacity = '0';
            mainImage.style.transition = 'opacity 0.3s ease-in-out';

            setTimeout(function () {
              mainImage.src = imageUrl;

              setTimeout(function () {
                mainImage.style.opacity = '1';
              }, 50);
            }, 300);
          }

          // Hide all mobile images on desktop
          const allMobileImages = wrapper.querySelectorAll('.service-image-mobile');
          allMobileImages.forEach(function (img) {
            img.style.display = 'none';
          });
        }
      }

      // Handle click on service items
      serviceItems.forEach(function (item) {
        item.addEventListener('click', function () {
          const imageUrl = this.getAttribute('data-image-url');
          const serviceIndex = this.getAttribute('data-service-index');

          if (!imageUrl) {
            return;
          }

          switchImage(this, imageUrl, serviceIndex);
        });
      });

      // Show initial active service image on mobile
      const activeItem = wrapper.querySelector('.service-item.active');
      if (activeItem && isMobile()) {
        const imageUrl = activeItem.getAttribute('data-image-url');
        const serviceIndex = activeItem.getAttribute('data-service-index');
        if (imageUrl) {
          switchImage(activeItem, imageUrl, serviceIndex);
        }
      }
    });
  }

  // Initialize on DOM ready
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initOurServices);
  } else {
    initOurServices();
  }

  // Re-initialize for dynamic content (e.g., AJAX loaded content)
  if (typeof jQuery !== 'undefined') {
    jQuery(document).on('bricks/ajax/after', initOurServices);
  }
})();

