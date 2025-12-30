<?php

/**
 * Register custom scripts early so Bricks can enqueue them automatically
 */
add_action('wp_enqueue_scripts', function () {
	// Register Our Services script (needs to be registered, Bricks will enqueue it automatically)
	wp_register_script(
		'our-services-script',
		get_stylesheet_directory_uri() . '/js/our-services.js',
		[],
		filemtime(get_stylesheet_directory() . '/js/our-services.js'),
		true
	);

	// Register Filtered Posts script
	wp_register_script(
		'filtered-posts-script',
		get_stylesheet_directory_uri() . '/js/filtered-posts.js',
		[],
		filemtime(get_stylesheet_directory() . '/js/filtered-posts.js'),
		true
	);

	// Localize script for AJAX
	wp_localize_script('filtered-posts-script', 'filteredPostsData', [
		'ajaxUrl' => admin_url('admin-ajax.php'),
		'nonce'   => wp_create_nonce('filtered_posts_nonce'),
	]);

	// Register Featured Posts Carousel script
	wp_register_script(
		'featured-posts-carousel-script',
		get_stylesheet_directory_uri() . '/js/featured-posts-carousel.js',
		[],
		filemtime(get_stylesheet_directory() . '/js/featured-posts-carousel.js'),
		true
	);
}, 5);

/**
 * Register/enqueue custom scripts and styles
 */
add_action('wp_enqueue_scripts', function () {
	// Enqueue your files on the canvas & frontend, not the builder panel. Otherwise custom CSS might affect builder)
	if (! bricks_is_builder_main()) {
		wp_enqueue_style('bricks-child', get_stylesheet_uri(), ['bricks-frontend'], filemtime(get_stylesheet_directory() . '/style.css'));
	}
});

/**
 * Register custom elements
 */
add_action('init', function () {
	$element_files = [
		__DIR__ . '/elements/title.php',
		__DIR__ . '/elements/our-services.php',
		__DIR__ . '/elements/filtered-posts.php',
		__DIR__ . '/elements/featured-posts-carousel.php',
	];

	foreach ($element_files as $file) {
		\Bricks\Elements::register_element($file);
	}
}, 11);

/**
 * Add text strings to builder
 */
add_filter('bricks/builder/i18n', function ($i18n) {
	// For element category 'custom'
	$i18n['custom'] = esc_html__('Custom', 'bricks');

	return $i18n;
});

function add_inline_script()
{

	// Make sure GSAP is enqueued first
	wp_enqueue_script(
		'gsap',
		'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js',
		[],
		null,
		true
	);

	wp_add_inline_script(
		'gsap',
		<<<JS
    window.addEventListener('load', () => {

        // GSAP Timeline
        const tl = gsap.timeline();

        // Birds animation
        tl.to('.mountain', { opacity: 1, duration: 1.5, ease: 'power3.out' }, 0);

        

        // Headlines and text
        tl.to('.heading-1', { opacity: 1, y: 0, duration: 1.2, ease: 'power3.out' }, 0.6);
        tl.to('.heading-2', { opacity: 1, y: 0, duration: 1.2, ease: 'power3.out' }, 0.7);
        tl.to('.text-1', { opacity: 1, y: 0, duration: 1.2, ease: 'power3.out' }, 0.8);
        tl.to('.text-2', { opacity: 1, y: 0, duration: 1.2, ease: 'power3.out' }, 0.9);
        tl.to('.spotlight-animation-container .bricks-background-primary', { opacity: 1, y: 0, duration: 1.2, ease: 'power3.out' }, 1);

        // Birds
        tl.to('.birds2', { left: '46%', duration: 1, ease: 'power3.out' }, 1.2);
        tl.to('.birds1', { left: '60%', duration: 1, ease: 'power3.out' }, 1.2);

        // Hiking
        tl.to('.hiking', { right: '-3%', duration: 1, ease: 'none' }, 1)
          .to('.hiking', { right: '-4%', duration: 1, ease: 'none' }, '>')
          .to('.hiking', { right: '-3%', duration: 1, ease: 'none' }, '>');

        // --------------------------
        // Mouse follow effect AFTER timeline
        // --------------------------
        tl.call(() => {
            const elements = [
                { el: document.querySelector('.birds1'), factor: 0.03 },
                { el: document.querySelector('.birds2'), factor: 0.02 },
                { el: document.querySelector('.hiking'), factor: 0.01 }
            ];

            window.addEventListener('mousemove', (e) => {
                const centerX = window.innerWidth / 2;
                const centerY = window.innerHeight / 2;

                elements.forEach(({el, factor}) => {
                    if (!el) return;

                    const xMove = (e.clientX - centerX) * factor;
                    const yMove = (e.clientY - centerY) * factor;

                    gsap.to(el, {
                        x: xMove,
                        y: yMove,
                        duration: 0.5,
                        ease: "power3.out",
                        overwrite: "auto"
                    });
                });
            });
        });
    });
JS
	);
}
add_action('wp_enqueue_scripts', 'add_inline_script');

/**
 * AJAX handler for loading filtered posts
 */
add_action('wp_ajax_filtered_posts_load', 'handle_filtered_posts_ajax');
add_action('wp_ajax_nopriv_filtered_posts_load', 'handle_filtered_posts_ajax');

function handle_filtered_posts_ajax()
{
	// Verify nonce
	if (! isset($_POST['nonce']) || ! wp_verify_nonce($_POST['nonce'], 'filtered_posts_nonce')) {
		wp_send_json_error(['message' => 'Invalid nonce']);
	}

	$instance_id = isset($_POST['instance_id']) ? sanitize_text_field($_POST['instance_id']) : '';
	$post_type = isset($_POST['post_type']) ? sanitize_text_field($_POST['post_type']) : 'post';
	$taxonomy = isset($_POST['taxonomy']) ? sanitize_text_field($_POST['taxonomy']) : 'category';
	$posts_per_page = isset($_POST['posts_per_page']) ? intval($_POST['posts_per_page']) : 6;
	$category_id = isset($_POST['category_id']) ? intval($_POST['category_id']) : 0;
	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;

	// Get element settings from AJAX request
	$settings_json = isset($_POST['settings']) ? wp_unslash($_POST['settings']) : '{}';
	$settings = json_decode($settings_json, true);

	// Default settings if not provided
	$settings = wp_parse_args($settings, [
		'showImage' => true,
		'imageSize' => 'large',
		'showCategory' => true,
		'categoryLabel' => 'Industry',
		'showExcerpt' => true,
		'excerptLength' => 100,
		'readMoreText' => 'Read More',
		'showPagination' => false,
		'prevText' => 'Prev',
		'nextText' => 'Next',
	]);

	// Ensure showPagination is boolean (handle string "false" from JSON)
	if (isset($settings['showPagination'])) {
		$settings['showPagination'] = filter_var($settings['showPagination'], FILTER_VALIDATE_BOOLEAN);
	}

	// Build query args
	$query_args = [
		'post_type'      => $post_type,
		'posts_per_page' => $posts_per_page,
		'paged'           => $page,
		'post_status'    => 'publish',
	];

	// Add taxonomy filter if category is selected
	if ($category_id > 0) {
		$query_args['tax_query'] = [
			[
				'taxonomy' => $taxonomy,
				'field'    => 'term_id',
				'terms'    => $category_id,
			],
		];
	}

	// Execute query
	$query = new WP_Query($query_args);

	// Generate HTML
	ob_start();

	if ($query->have_posts()) {
		while ($query->have_posts()) {
			$query->the_post();
			$post_id = get_the_ID();

			echo '<article class="post-card">';

			// Featured image
			if ($settings['showImage'] && has_post_thumbnail($post_id)) {
				$image_url = get_the_post_thumbnail_url($post_id, $settings['imageSize']);
				$image_alt = get_post_meta(get_post_thumbnail_id($post_id), '_wp_attachment_image_alt', true) ?: get_the_title();

				echo '<div class="post-card-image-wrapper">';
				echo '<img src="' . esc_url($image_url) . '" alt="' . esc_attr($image_alt) . '" class="post-card-image">';
				echo '</div>';
			}

			// Card content
			echo '<div class="post-card-content">';

			// Category tag (before title)
			if ($settings['showCategory']) {
				$post_terms = wp_get_post_terms($post_id, $taxonomy);
				if (! empty($post_terms) && ! is_wp_error($post_terms)) {
					$first_term = $post_terms[0];
					$category_label = isset($settings['categoryLabel']) ? $settings['categoryLabel'] : 'Industry';

					// Render dynamic data in category label
					$rendered_label = bricks_render_dynamic_data($category_label, $post_id, 'text');

					// If dynamic data tag was used and rendered, use it; otherwise use the label as-is
					if (strpos($category_label, '{') !== false && $rendered_label !== $category_label) {
						// Strip HTML tags to get only the text (in case dynamic data returns HTML with links)
						$category_display = wp_strip_all_tags($rendered_label);
					} else {
						// Use the first term name if label is empty or use label as fallback
						$category_display = ! empty($rendered_label) ? wp_strip_all_tags($rendered_label) : $first_term->name;
					}

					echo '<span class="post-card-category">' . esc_html($category_display) . '</span>';
				}
			}

			// Title
			echo '<h3 class="post-card-title">';
			echo '<a href="' . esc_url(get_permalink($post_id)) . '">' . esc_html(get_the_title()) . '</a>';
			echo '</h3>';

			// Excerpt
			if ($settings['showExcerpt']) {
				$excerpt = get_the_excerpt($post_id);
				if (empty($excerpt)) {
					$excerpt = wp_trim_words(get_the_content($post_id), $settings['excerptLength']);
				} else {
					$excerpt = wp_trim_words($excerpt, $settings['excerptLength']);
				}
				echo '<p class="post-card-excerpt">' . esc_html($excerpt) . '</p>';
			}

			// Read more link
			echo '<a href="' . esc_url(get_permalink($post_id)) . '" class="post-card-read-more">';
			echo esc_html($settings['readMoreText']);
			echo '<svg class="read-more-arrow" width="16" height="27" viewBox="0 0 16 27" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M2.36667 26.6667L0 24.3L10.9667 13.3333L0 2.36667L2.36667 0L15.7 13.3333L2.36667 26.6667Z" fill="currentColor"/></svg>';
			echo '</a>';

			echo '</div>';
			echo '</article>';
		}
		wp_reset_postdata();
	} else {
		echo '<p class="no-posts">' . esc_html__('No posts found.', 'bricks') . '</p>';
	}

	$html = ob_get_clean();

	// Generate pagination HTML
	ob_start();

	// Check if pagination should be shown (must be explicitly true and have multiple pages)
	$show_pagination = ! empty($settings['showPagination']) && $query->max_num_pages > 1;

	if ($show_pagination) {
		echo '<div class="filtered-posts-pagination">';

		// Previous button
		if ($page > 1) {
			echo '<button class="pagination-prev" data-page="' . esc_attr($page - 1) . '">';
			echo '<svg width="16" height="27" viewBox="0 0 16 27" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M13.6333 0L16 2.36667L5.03333 13.3333L16 24.3L13.6333 26.6667L0.3 13.3333L13.6333 0Z" fill="currentColor"/></svg>';
			echo esc_html($settings['prevText']);
			echo '</button>';
		} else {
			echo '<button class="pagination-prev disabled" disabled>';
			echo '<svg width="16" height="27" viewBox="0 0 16 27" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M13.6333 0L16 2.36667L5.03333 13.3333L16 24.3L13.6333 26.6667L0.3 13.3333L13.6333 0Z" fill="currentColor"/></svg>';
			echo esc_html($settings['prevText']);
			echo '</button>';
		}

		// Page numbers (centered)
		echo '<div class="pagination-numbers">';
		$max_pages = $query->max_num_pages;
		$range = 2; // Number of pages to show on each side of current page

		// Calculate start and end page numbers
		$start = max(1, $page - $range);
		$end = min($max_pages, $page + $range);

		// Show first page if not in range
		if ($start > 1) {
			echo '<button class="pagination-number" data-page="1">1</button>';
			if ($start > 2) {
				echo '<span class="pagination-dots">...</span>';
			}
		}

		// Show page numbers in range
		for ($i = $start; $i <= $end; $i++) {
			$active_class = ($i === $page) ? ' active' : '';
			echo '<button class="pagination-number' . esc_attr($active_class) . '" data-page="' . esc_attr($i) . '">' . esc_html($i) . '</button>';
		}

		// Show last page if not in range
		if ($end < $max_pages) {
			if ($end < $max_pages - 1) {
				echo '<span class="pagination-dots">...</span>';
			}
			echo '<button class="pagination-number" data-page="' . esc_attr($max_pages) . '">' . esc_html($max_pages) . '</button>';
		}

		echo '</div>'; // .pagination-numbers

		// Next button
		if ($page < $query->max_num_pages) {
			echo '<button class="pagination-next" data-page="' . esc_attr($page + 1) . '">';
			echo esc_html($settings['nextText']);
			echo '<svg width="16" height="27" viewBox="0 0 16 27" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M2.36667 26.6667L0 24.3L10.9667 13.3333L0 2.36667L2.36667 0L15.7 13.3333L2.36667 26.6667Z" fill="currentColor"/></svg>';
			echo '</button>';
		} else {
			echo '<button class="pagination-next disabled" disabled>';
			echo esc_html($settings['nextText']);
			echo '<svg width="16" height="27" viewBox="0 0 16 27" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M2.36667 26.6667L0 24.3L10.9667 13.3333L0 2.36667L2.36667 0L15.7 13.3333L2.36667 26.6667Z" fill="currentColor"/></svg>';
			echo '</button>';
		}

		echo '</div>';
	}

	$pagination_html = ob_get_clean();

	wp_send_json_success([
		'html' => $html,
		'pagination' => $pagination_html,
	]);
}
