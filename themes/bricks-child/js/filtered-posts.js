(function () {
  'use strict';

  /**
   * Initialize filtered posts functionality
   */
  function initFilteredPosts() {
    const wrappers = document.querySelectorAll('.filtered-posts-wrapper');

    wrappers.forEach(wrapper => {

      // Prevent double initialization (important for Bricks)
      if (wrapper.dataset.fpReady === '1') return;
      wrapper.dataset.fpReady = '1';

      const instanceId = wrapper.getAttribute('data-instance-id');
      const postType = wrapper.getAttribute('data-post-type') || 'post';
      const taxonomy = wrapper.getAttribute('data-taxonomy') || 'category';
      const postsPerPage = parseInt(wrapper.getAttribute('data-posts-per-page')) || 6;
      const settings = JSON.parse(wrapper.getAttribute('data-settings') || '{}');

      const filterItems = wrapper.querySelectorAll('.filter-item');
      const postsGrid = wrapper.querySelector('.filtered-posts-grid');
      const contentArea = wrapper.querySelector('.filtered-posts-content');

      // --- READ INITIAL STATE FROM URL ---
      const urlParams = new URLSearchParams(window.location.search);
      const initialCategory = urlParams.get('fp_category') || '0';
      const initialPage = parseInt(urlParams.get('fp_page')) || 1;

      // --- SET ACTIVE FILTER ---
      filterItems.forEach(item => {
        item.classList.toggle(
          'active',
          item.getAttribute('data-category') === initialCategory
        );
      });

      // --- FILTER CLICK HANDLER ---
      filterItems.forEach(item => {
        item.addEventListener('click', function (e) {
          e.preventDefault();

          const categoryId = this.getAttribute('data-category');

          filterItems.forEach(fi => fi.classList.remove('active'));
          this.classList.add('active');

          loadFilteredPosts(
            wrapper,
            instanceId,
            postType,
            taxonomy,
            postsPerPage,
            categoryId,
            1,
            settings,
            false // <-- user-triggered load
          );
        });
      });

      // --- INITIAL AJAX LOAD (IMPORTANT FIX) ---
      loadFilteredPosts(
        wrapper,
        instanceId,
        postType,
        taxonomy,
        postsPerPage,
        initialCategory,
        initialPage,
        settings,
        true // <-- first load, no scroll
      );
    });
  }

  /**
   * Load filtered posts via AJAX
   */
  function loadFilteredPosts(wrapper, instanceId, postType, taxonomy, postsPerPage, categoryId, page, settings, firstLoad = false) {
    const postsGrid = wrapper.querySelector('.filtered-posts-grid');
    const contentArea = wrapper.querySelector('.filtered-posts-content');
    let pagination = wrapper.querySelector('.filtered-posts-pagination');

    if (!postsGrid) return;

    // Loading state
    postsGrid.style.opacity = '0.5';
    postsGrid.style.pointerEvents = 'none';

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

    fetch(filteredPostsData.ajaxUrl, {
      method: 'POST',
      body: formData
    })
      .then(res => res.json())
      .then(data => {
        if (!data.success) return;

        // Update posts
        postsGrid.innerHTML = data.data.html || '';

        // Handle pagination
        const hasPagination = data.data.pagination && data.data.pagination.trim() !== '';

        if (hasPagination) {
          if (!pagination) {
            pagination = document.createElement('div');
            pagination.className = 'filtered-posts-pagination';
            contentArea.appendChild(pagination);
          }
          pagination.innerHTML = data.data.pagination;
          attachPaginationListeners(wrapper, instanceId, postType, taxonomy, postsPerPage, settings);
        } else if (pagination) {
          pagination.remove();
        }

        // Update URL
        const url = new URL(window.location);
        categoryId === '0'
          ? url.searchParams.delete('fp_category')
          : url.searchParams.set('fp_category', categoryId);

        page === 1
          ? url.searchParams.delete('fp_page')
          : url.searchParams.set('fp_page', page);

        window.history.pushState({}, '', url);

        // Only scroll if NOT first load
        if (!firstLoad) {
          postsGrid.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
      })
      .catch(err => console.error('Filtered posts AJAX error:', err))
      .finally(() => {
        postsGrid.style.opacity = '1';
        postsGrid.style.pointerEvents = 'auto';
      });
  }

  /**
   * Pagination listeners
   */
  function attachPaginationListeners(wrapper, instanceId, postType, taxonomy, postsPerPage, settings) {
    const pagination = wrapper.querySelector('.filtered-posts-pagination');
    if (!pagination) return;

    const buttons = pagination.querySelectorAll('button');

    buttons.forEach(btn => {
      btn.addEventListener('click', function (e) {
        e.preventDefault();
        if (this.classList.contains('disabled')) return;

        const page = parseInt(this.getAttribute('data-page')) || 1;
        const activeFilter = wrapper.querySelector('.filter-item.active');
        const categoryId = activeFilter ? activeFilter.getAttribute('data-category') : '0';

        loadFilteredPosts(
          wrapper,
          instanceId,
          postType,
          taxonomy,
          postsPerPage,
          categoryId,
          page,
          settings,
          false // <-- user-triggered load
        );
      });
    });
  }

  // DOM Ready
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initFilteredPosts);
  } else {
    initFilteredPosts();
  }

  // Bricks live editor support
  if (typeof window.bricksData !== 'undefined') {
    document.addEventListener(
      'bricks/ajax/query_result/displayed',
      initFilteredPosts
    );
  }
})();
