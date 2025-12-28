(function () {
  'use strict';

  /**
   * Initialize filtered posts functionality
   */
  function initFilteredPosts() {
    const wrappers = document.querySelectorAll('.filtered-posts-wrapper');

    wrappers.forEach(wrapper => {
      const instanceId = wrapper.getAttribute('data-instance-id');
      const postType = wrapper.getAttribute('data-post-type') || 'post';
      const taxonomy = wrapper.getAttribute('data-taxonomy') || 'category';
      const postsPerPage = parseInt(wrapper.getAttribute('data-posts-per-page')) || 6;
      const settings = JSON.parse(wrapper.getAttribute('data-settings') || '{}');

      // Filter items
      const filterItems = wrapper.querySelectorAll('.filter-item');
      const postsGrid = wrapper.querySelector('.filtered-posts-grid');
      const pagination = wrapper.querySelector('.filtered-posts-pagination');

      // Handle filter clicks
      filterItems.forEach(item => {
        item.addEventListener('click', function (e) {
          e.preventDefault();

          const categoryId = this.getAttribute('data-category');

          // Update active state
          filterItems.forEach(fi => fi.classList.remove('active'));
          this.classList.add('active');

          // Load posts via AJAX
          loadFilteredPosts(wrapper, instanceId, postType, taxonomy, postsPerPage, categoryId, 1, settings);
        });
      });

      // Handle pagination clicks
      if (pagination) {
        const prevBtn = pagination.querySelector('.pagination-prev:not(.disabled)');
        const nextBtn = pagination.querySelector('.pagination-next:not(.disabled)');
        const pageNumbers = pagination.querySelectorAll('.pagination-number');

        if (prevBtn) {
          prevBtn.addEventListener('click', function (e) {
            e.preventDefault();
            const page = parseInt(this.getAttribute('data-page')) || 1;
            const activeFilter = wrapper.querySelector('.filter-item.active');
            const categoryId = activeFilter ? activeFilter.getAttribute('data-category') : '0';

            loadFilteredPosts(wrapper, instanceId, postType, taxonomy, postsPerPage, categoryId, page, settings);
          });
        }

        if (nextBtn) {
          nextBtn.addEventListener('click', function (e) {
            e.preventDefault();
            const page = parseInt(this.getAttribute('data-page')) || 1;
            const activeFilter = wrapper.querySelector('.filter-item.active');
            const categoryId = activeFilter ? activeFilter.getAttribute('data-category') : '0';

            loadFilteredPosts(wrapper, instanceId, postType, taxonomy, postsPerPage, categoryId, page, settings);
          });
        }

        // Handle page number clicks
        pageNumbers.forEach(btn => {
          btn.addEventListener('click', function (e) {
            e.preventDefault();
            const page = parseInt(this.getAttribute('data-page')) || 1;
            const activeFilter = wrapper.querySelector('.filter-item.active');
            const categoryId = activeFilter ? activeFilter.getAttribute('data-category') : '0';

            loadFilteredPosts(wrapper, instanceId, postType, taxonomy, postsPerPage, categoryId, page, settings);
          });
        });
      }
    });
  }

  /**
   * Load filtered posts via AJAX
   */
  function loadFilteredPosts(wrapper, instanceId, postType, taxonomy, postsPerPage, categoryId, page, settings) {
    const postsGrid = wrapper.querySelector('.filtered-posts-grid');
    const contentArea = wrapper.querySelector('.filtered-posts-content');
    let pagination = wrapper.querySelector('.filtered-posts-pagination');

    // Show loading state
    if (postsGrid) {
      postsGrid.style.opacity = '0.5';
      postsGrid.style.pointerEvents = 'none';
    }

    // Build request data
    const formData = new FormData();
    formData.append('action', 'filtered_posts_load');
    formData.append('instance_id', instanceId);
    formData.append('post_type', postType);
    formData.append('taxonomy', taxonomy);
    formData.append('posts_per_page', postsPerPage);
    formData.append('category_id', categoryId);
    formData.append('page', page);
    formData.append('settings', JSON.stringify(settings));
    formData.append('nonce', filteredPostsData.nonce);

    // Make AJAX request
    fetch(filteredPostsData.ajaxUrl, {
      method: 'POST',
      body: formData
    })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          // Update posts grid
          if (postsGrid && data.data.html) {
            postsGrid.innerHTML = data.data.html;
          }

          // Update pagination
          const shouldShowPagination = data.data.pagination && data.data.pagination.trim() !== '';

          if (shouldShowPagination) {
            // Pagination should be shown - create or update element
            if (!pagination && contentArea) {
              // Create pagination element if it doesn't exist
              pagination = document.createElement('div');
              pagination.className = 'filtered-posts-pagination';
              contentArea.appendChild(pagination);
            }

            if (pagination) {
              pagination.innerHTML = data.data.pagination;
              // Re-attach pagination event listeners
              attachPaginationListeners(wrapper, instanceId, postType, taxonomy, postsPerPage, settings);
            }
          } else {
            // Pagination should be hidden - remove element if it exists
            if (pagination) {
              pagination.remove();
            }
          }

          // Update URL without page reload
          const url = new URL(window.location);
          if (categoryId === '0') {
            url.searchParams.delete('fp_category');
          } else {
            url.searchParams.set('fp_category', categoryId);
          }
          if (page === 1) {
            url.searchParams.delete('fp_page');
          } else {
            url.searchParams.set('fp_page', page);
          }
          window.history.pushState({}, '', url);

          // Scroll to top of grid
          if (postsGrid) {
            postsGrid.scrollIntoView({ behavior: 'smooth', block: 'start' });
          }
        } else {
          console.error('Error loading posts:', data.data);
        }
      })
      .catch(error => {
        console.error('AJAX error:', error);
      })
      .finally(() => {
        // Remove loading state
        if (postsGrid) {
          postsGrid.style.opacity = '1';
          postsGrid.style.pointerEvents = 'auto';
        }
      });
  }

  /**
   * Attach pagination event listeners
   */
  function attachPaginationListeners(wrapper, instanceId, postType, taxonomy, postsPerPage, settings) {
    const pagination = wrapper.querySelector('.filtered-posts-pagination');
    if (!pagination) return;

    const prevBtn = pagination.querySelector('.pagination-prev:not(.disabled)');
    const nextBtn = pagination.querySelector('.pagination-next:not(.disabled)');
    const pageNumbers = pagination.querySelectorAll('.pagination-number');

    if (prevBtn) {
      prevBtn.addEventListener('click', function (e) {
        e.preventDefault();
        const page = parseInt(this.getAttribute('data-page')) || 1;
        const activeFilter = wrapper.querySelector('.filter-item.active');
        const categoryId = activeFilter ? activeFilter.getAttribute('data-category') : '0';

        loadFilteredPosts(wrapper, instanceId, postType, taxonomy, postsPerPage, categoryId, page, settings);
      });
    }

    if (nextBtn) {
      nextBtn.addEventListener('click', function (e) {
        e.preventDefault();
        const page = parseInt(this.getAttribute('data-page')) || 1;
        const activeFilter = wrapper.querySelector('.filter-item.active');
        const categoryId = activeFilter ? activeFilter.getAttribute('data-category') : '0';

        loadFilteredPosts(wrapper, instanceId, postType, taxonomy, postsPerPage, categoryId, page, settings);
      });
    }

    // Handle page number clicks
    pageNumbers.forEach(btn => {
      btn.addEventListener('click', function (e) {
        e.preventDefault();
        const page = parseInt(this.getAttribute('data-page')) || 1;
        const activeFilter = wrapper.querySelector('.filter-item.active');
        const categoryId = activeFilter ? activeFilter.getAttribute('data-category') : '0';

        loadFilteredPosts(wrapper, instanceId, postType, taxonomy, postsPerPage, categoryId, page, settings);
      });
    });
  }

  // Initialize on DOM ready
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initFilteredPosts);
  } else {
    initFilteredPosts();
  }

  // Re-initialize after AJAX content loads (for Bricks builder)
  if (typeof window.bricksData !== 'undefined') {
    document.addEventListener('bricks/ajax/query_result/displayed', initFilteredPosts);
  }
})();

