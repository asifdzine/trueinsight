<?php
if (! defined('ABSPATH')) exit; // Exit if accessed directly

class Element_Filtered_Posts extends \Bricks\Element
{
  public $category     = 'custom';
  public $name         = 'filtered-posts';
  public $icon         = 'fas fa-filter';
  public $scripts      = ['filtered-posts-script'];

  public function get_label()
  {
    return esc_html__('Filtered Posts', 'bricks');
  }

  public function set_control_groups()
  {
    $this->control_groups['query'] = [
      'title' => esc_html__('Query', 'bricks'),
      'tab'   => 'content',
    ];

    $this->control_groups['filter'] = [
      'title' => esc_html__('Filter Sidebar', 'bricks'),
      'tab'   => 'content',
    ];

    $this->control_groups['card'] = [
      'title' => esc_html__('Card', 'bricks'),
      'tab'   => 'content',
    ];

    $this->control_groups['pagination'] = [
      'title' => esc_html__('Pagination', 'bricks'),
      'tab'   => 'content',
    ];

    $this->control_groups['layout'] = [
      'title' => esc_html__('Layout', 'bricks'),
      'tab'   => 'style',
    ];

    $this->control_groups['filterStyle'] = [
      'title' => esc_html__('Filter Style', 'bricks'),
      'tab'   => 'style',
    ];

    $this->control_groups['cardStyle'] = [
      'title' => esc_html__('Card Style', 'bricks'),
      'tab'   => 'style',
    ];

    $this->control_groups['paginationStyle'] = [
      'title' => esc_html__('Pagination Style', 'bricks'),
      'tab'   => 'style',
    ];
  }

  public function set_controls()
  {
    // QUERY CONTROLS
    $this->controls['postType'] = [
      'tab'         => 'content',
      'group'       => 'query',
      'label'       => esc_html__('Post Type', 'bricks'),
      'type'        => 'select',
      'options'     => $this->get_post_types(),
      'placeholder' => esc_html__('post', 'bricks'),
      'default'     => 'post',
    ];

    $this->controls['postsPerPage'] = [
      'tab'         => 'content',
      'group'       => 'query',
      'label'       => esc_html__('Posts Per Page', 'bricks'),
      'type'        => 'number',
      'min'         => 1,
      'default'     => 6,
      'placeholder' => 6,
    ];

    $this->controls['taxonomy'] = [
      'tab'         => 'content',
      'group'       => 'query',
      'label'       => esc_html__('Taxonomy for Filtering', 'bricks'),
      'type'        => 'select',
      'options'     => $this->get_taxonomies(),
      'placeholder' => esc_html__('category', 'bricks'),
      'default'     => 'category',
      'description' => esc_html__('Select the taxonomy to use for filtering (e.g., category, post_tag, or custom taxonomy)', 'bricks'),
    ];

    $this->controls['hideEmptyTerms'] = [
      'tab'         => 'content',
      'group'       => 'query',
      'label'       => esc_html__('Hide Empty Terms', 'bricks'),
      'type'        => 'checkbox',
      'default'     => false,
      'description' => esc_html__('If checked, only show terms that have posts assigned. If unchecked, show all terms.', 'bricks'),
    ];

    // FILTER SIDEBAR CONTROLS
    $this->controls['filterTitle'] = [
      'tab'         => 'content',
      'group'       => 'filter',
      'label'       => esc_html__('Filter Title', 'bricks'),
      'type'        => 'text',
      'default'     => esc_html__('Filter by Industry', 'bricks'),
      'hasDynamicData' => 'text',
    ];

    $this->controls['showViewAll'] = [
      'tab'     => 'content',
      'group'   => 'filter',
      'label'   => esc_html__('Show "View All" Option', 'bricks'),
      'type'    => 'checkbox',
      'default' => true,
    ];

    $this->controls['viewAllText'] = [
      'tab'         => 'content',
      'group'       => 'filter',
      'label'       => esc_html__('"View All" Text', 'bricks'),
      'type'        => 'text',
      'default'     => esc_html__('View All', 'bricks'),
      'required'    => ['showViewAll', '=', true],
    ];

    // CARD CONTROLS
    $this->controls['showImage'] = [
      'tab'     => 'content',
      'group'   => 'card',
      'label'   => esc_html__('Show Featured Image', 'bricks'),
      'type'    => 'checkbox',
      'default' => true,
    ];

    $this->controls['imageSize'] = [
      'tab'         => 'content',
      'group'       => 'card',
      'label'       => esc_html__('Image Size', 'bricks'),
      'type'        => 'select',
      'options'     => $this->get_image_sizes(),
      'default'     => 'large',
      'required'    => ['showImage', '=', true],
    ];

    $this->controls['showCategory'] = [
      'tab'     => 'content',
      'group'   => 'card',
      'label'   => esc_html__('Show Category Tag', 'bricks'),
      'type'    => 'checkbox',
      'default' => true,
    ];

    $this->controls['categoryLabel'] = [
      'tab'            => 'content',
      'group'          => 'card',
      'label'          => esc_html__('Category Label Text', 'bricks'),
      'type'           => 'text',
      'hasDynamicData' => 'text',
      'default'        => esc_html__('Industry', 'bricks'),
      'required'       => ['showCategory', '=', true],
      'description'    => esc_html__('Use {post_terms_category} to show the actual category name, or enter custom text.', 'bricks'),
    ];

    $this->controls['showExcerpt'] = [
      'tab'     => 'content',
      'group'   => 'card',
      'label'   => esc_html__('Show Excerpt', 'bricks'),
      'type'    => 'checkbox',
      'default' => true,
    ];

    $this->controls['excerptLength'] = [
      'tab'         => 'content',
      'group'       => 'card',
      'label'       => esc_html__('Excerpt Length', 'bricks'),
      'type'        => 'number',
      'min'         => 10,
      'max'         => 200,
      'default'     => 100,
      'required'    => ['showExcerpt', '=', true],
    ];

    $this->controls['readMoreText'] = [
      'tab'         => 'content',
      'group'       => 'card',
      'label'       => esc_html__('Read More Text', 'bricks'),
      'type'        => 'text',
      'default'     => esc_html__('Read More', 'bricks'),
    ];

    // PAGINATION CONTROLS
    $this->controls['showPagination'] = [
      'tab'     => 'content',
      'group'   => 'pagination',
      'label'   => esc_html__('Show Pagination', 'bricks'),
      'type'    => 'checkbox',
      'default' => false,
    ];

    $this->controls['prevText'] = [
      'tab'         => 'content',
      'group'       => 'pagination',
      'label'       => esc_html__('Previous Text', 'bricks'),
      'type'        => 'text',
      'default'     => esc_html__('Prev', 'bricks'),
      'required'    => ['showPagination', '=', true],
    ];

    $this->controls['nextText'] = [
      'tab'         => 'content',
      'group'       => 'pagination',
      'label'       => esc_html__('Next Text', 'bricks'),
      'type'        => 'text',
      'default'     => esc_html__('Next', 'bricks'),
      'required'    => ['showPagination', '=', true],
    ];

    // LAYOUT STYLE CONTROLS
    $this->controls['sidebarWidth'] = [
      'tab'     => 'style',
      'group'   => 'layout',
      'label'   => esc_html__('Sidebar Width', 'bricks'),
      'type'    => 'number',
      'units'   => true,
      'css'     => [
        [
          'property' => 'width',
          'selector' => '.filtered-posts-sidebar',
        ],
      ],
      'default' => '300px',
    ];

    $this->controls['columns'] = [
      'tab'         => 'style',
      'group'       => 'layout',
      'label'       => esc_html__('Columns', 'bricks'),
      'type'        => 'number',
      'min'         => 1,
      'max'         => 6,
      'default'     => 2,
      'css'         => [
        [
          'property' => '--columns',
          'selector' => '.filtered-posts-grid',
        ],
      ],
    ];

    $this->controls['gap'] = [
      'tab'     => 'style',
      'group'   => 'layout',
      'label'   => esc_html__('Grid Gap', 'bricks'),
      'type'    => 'number',
      'units'   => true,
      'css'     => [
        [
          'property' => 'gap',
          'selector' => '.filtered-posts-grid',
        ],
      ],
      'default' => '30px',
    ];

    // FILTER STYLE CONTROLS
    $this->controls['filterTitleTypography'] = [
      'tab'     => 'style',
      'group'   => 'filterStyle',
      'label'   => esc_html__('Filter Title Typography', 'bricks'),
      'type'    => 'typography',
      'css'     => [
        [
          'property' => 'typography',
          'selector' => '.filter-title',
        ],
      ],
    ];

    $this->controls['filterItemTypography'] = [
      'tab'     => 'style',
      'group'   => 'filterStyle',
      'label'   => esc_html__('Filter Item Typography', 'bricks'),
      'type'    => 'typography',
      'css'     => [
        [
          'property' => 'typography',
          'selector' => '.filter-item',
        ],
      ],
    ];

    $this->controls['filterItemPadding'] = [
      'tab'     => 'style',
      'group'   => 'filterStyle',
      'label'   => esc_html__('Filter Item Padding', 'bricks'),
      'type'    => 'spacing',
      'css'     => [
        [
          'property' => 'padding',
          'selector' => '.filter-item',
        ],
      ],
    ];

    $this->controls['filterActiveBackground'] = [
      'tab'     => 'style',
      'group'   => 'filterStyle',
      'label'   => esc_html__('Active Filter Background', 'bricks'),
      'type'    => 'color',
      'css'     => [
        [
          'property' => 'background-color',
          'selector' => '.filter-item.active',
        ],
      ],
    ];

    $this->controls['filterActiveColor'] = [
      'tab'     => 'style',
      'group'   => 'filterStyle',
      'label'   => esc_html__('Active Filter Text Color', 'bricks'),
      'type'    => 'color',
      'css'     => [
        [
          'property' => 'color',
          'selector' => '.filter-item.active',
        ],
      ],
    ];

    // CARD STYLE CONTROLS
    $this->controls['cardTitleTypography'] = [
      'tab'     => 'style',
      'group'   => 'cardStyle',
      'label'   => esc_html__('Card Title Typography', 'bricks'),
      'type'    => 'typography',
      'css'     => [
        [
          'property' => 'typography',
          'selector' => '.post-card-title',
        ],
      ],
    ];

    $this->controls['cardExcerptTypography'] = [
      'tab'     => 'style',
      'group'   => 'cardStyle',
      'label'   => esc_html__('Card Excerpt Typography', 'bricks'),
      'type'    => 'typography',
      'css'     => [
        [
          'property' => 'typography',
          'selector' => '.post-card-excerpt',
        ],
      ],
    ];

    $this->controls['cardCategoryTypography'] = [
      'tab'     => 'style',
      'group'   => 'cardStyle',
      'label'   => esc_html__('Category Tag Typography', 'bricks'),
      'type'    => 'typography',
      'css'     => [
        [
          'property' => 'typography',
          'selector' => '.post-card-category',
        ],
      ],
    ];

    $this->controls['cardReadMoreTypography'] = [
      'tab'     => 'style',
      'group'   => 'cardStyle',
      'label'   => esc_html__('Read More Typography', 'bricks'),
      'type'    => 'typography',
      'css'     => [
        [
          'property' => 'typography',
          'selector' => '.post-card-read-more',
        ],
      ],
    ];

    $this->controls['cardPadding'] = [
      'tab'     => 'style',
      'group'   => 'cardStyle',
      'label'   => esc_html__('Card Padding', 'bricks'),
      'type'    => 'spacing',
      'css'     => [
        [
          'property' => 'padding',
          'selector' => '.post-card',
        ],
      ],
    ];

    $this->controls['cardBorderRadius'] = [
      'tab'     => 'style',
      'group'   => 'cardStyle',
      'label'   => esc_html__('Card Border Radius', 'bricks'),
      'type'    => 'dimensions',
      'css'     => [
        [
          'property' => 'border-radius',
          'selector' => '.post-card',
        ],
      ],
    ];

    $this->controls['cardBackground'] = [
      'tab'     => 'style',
      'group'   => 'cardStyle',
      'label'   => esc_html__('Card Background', 'bricks'),
      'type'    => 'color',
      'css'     => [
        [
          'property' => 'background-color',
          'selector' => '.post-card',
        ],
      ],
    ];

    // PAGINATION STYLE CONTROLS
    $this->controls['paginationTypography'] = [
      'tab'     => 'style',
      'group'   => 'paginationStyle',
      'label'   => esc_html__('Pagination Typography', 'bricks'),
      'type'    => 'typography',
      'css'     => [
        [
          'property' => 'typography',
          'selector' => '.filtered-posts-pagination button',
        ],
      ],
    ];

    $this->controls['paginationButtonBackground'] = [
      'tab'     => 'style',
      'group'   => 'paginationStyle',
      'label'   => esc_html__('Button Background', 'bricks'),
      'type'    => 'color',
      'css'     => [
        [
          'property' => 'background-color',
          'selector' => '.filtered-posts-pagination button',
        ],
      ],
    ];

    $this->controls['paginationButtonColor'] = [
      'tab'     => 'style',
      'group'   => 'paginationStyle',
      'label'   => esc_html__('Button Text Color', 'bricks'),
      'type'    => 'color',
      'css'     => [
        [
          'property' => 'color',
          'selector' => '.filtered-posts-pagination button',
        ],
      ],
    ];

    $this->controls['paginationButtonPadding'] = [
      'tab'     => 'style',
      'group'   => 'paginationStyle',
      'label'   => esc_html__('Button Padding', 'bricks'),
      'type'    => 'spacing',
      'css'     => [
        [
          'property' => 'padding',
          'selector' => '.filtered-posts-pagination button',
        ],
      ],
    ];
  }

  /**
   * Get available post types
   */
  private function get_post_types()
  {
    $post_types = get_post_types(['public' => true], 'objects');
    $options = [];

    foreach ($post_types as $post_type) {
      $options[$post_type->name] = $post_type->label;
    }

    return $options;
  }

  /**
   * Get available taxonomies
   */
  private function get_taxonomies()
  {
    $taxonomies = get_taxonomies(['public' => true], 'objects');
    $options = [];

    foreach ($taxonomies as $taxonomy) {
      $options[$taxonomy->name] = $taxonomy->label;
    }

    return $options;
  }

  /**
   * Get available image sizes
   */
  private function get_image_sizes()
  {
    $sizes = get_intermediate_image_sizes();
    $options = ['full' => esc_html__('Full', 'bricks')];

    foreach ($sizes as $size) {
      $options[$size] = ucfirst(str_replace(['-', '_'], ' ', $size));
    }

    return $options;
  }

  /**
   * Enqueue scripts
   */
  public function enqueue_scripts()
  {
    wp_enqueue_script('filtered-posts-script');
  }

  /**
   * Render element HTML on frontend
   */
  public function render()
  {
    $settings = $this->settings;
    $unique_id = $this->id;

    // Get settings
    $post_type = !empty($settings['postType']) ? $settings['postType'] : 'post';
    $posts_per_page = !empty($settings['postsPerPage']) ? intval($settings['postsPerPage']) : 6;
    $taxonomy = !empty($settings['taxonomy']) ? $settings['taxonomy'] : 'category';
    $hide_empty_terms = isset($settings['hideEmptyTerms']) ? $settings['hideEmptyTerms'] : false;
    $filter_title = !empty($settings['filterTitle']) ? $settings['filterTitle'] : esc_html__('Filter by Industry', 'bricks');
    $show_view_all = isset($settings['showViewAll']) ? $settings['showViewAll'] : true;
    $view_all_text = !empty($settings['viewAllText']) ? $settings['viewAllText'] : esc_html__('View All', 'bricks');
    $show_image = isset($settings['showImage']) ? $settings['showImage'] : true;
    $image_size = !empty($settings['imageSize']) ? $settings['imageSize'] : 'large';
    $show_category = isset($settings['showCategory']) ? $settings['showCategory'] : true;
    $category_label = !empty($settings['categoryLabel']) ? $settings['categoryLabel'] : esc_html__('Industry', 'bricks');
    $show_excerpt = isset($settings['showExcerpt']) ? $settings['showExcerpt'] : true;
    $excerpt_length = !empty($settings['excerptLength']) ? intval($settings['excerptLength']) : 100;
    $read_more_text = !empty($settings['readMoreText']) ? $settings['readMoreText'] : esc_html__('Read More', 'bricks');
    // Get showPagination setting and ensure it's boolean
    $show_pagination = false;
    if (isset($settings['showPagination'])) {
      $show_pagination = filter_var($settings['showPagination'], FILTER_VALIDATE_BOOLEAN);
    }
    $prev_text = !empty($settings['prevText']) ? $settings['prevText'] : esc_html__('Prev', 'bricks');
    $next_text = !empty($settings['nextText']) ? $settings['nextText'] : esc_html__('Next', 'bricks');

    // Get current page
    $paged = get_query_var('paged') ? get_query_var('paged') : 1;
    if (isset($_GET['fp_page'])) {
      $paged = intval($_GET['fp_page']);
    }

    // Get selected category from URL or default to all
    $selected_category = isset($_GET['fp_category']) ? intval($_GET['fp_category']) : 0;

    // Get default category ID to exclude "Uncategorized"
    $default_category_id = 0;
    if ($taxonomy === 'category') {
      $default_category_id = (int) get_option('default_category', 1);
    }

    // Get all terms for the taxonomy, excluding default/uncategorized
    $exclude_ids = $default_category_id > 0 ? [$default_category_id] : [];
    $terms = get_terms([
      'taxonomy'   => $taxonomy,
      'hide_empty' => $hide_empty_terms,
      'exclude'    => $exclude_ids,
    ]);

    // If no terms found or error, try to get terms without the hide_empty restriction
    if (is_wp_error($terms) || empty($terms)) {
      // Try getting all terms regardless of empty status
      $terms = get_terms([
        'taxonomy'   => $taxonomy,
        'hide_empty' => false,
        'exclude'    => $exclude_ids,
      ]);
    }

    // If still no terms, verify taxonomy exists and is registered for post type
    if (is_wp_error($terms) || empty($terms)) {
      $object_taxonomies = get_object_taxonomies($post_type, 'names');

      // If taxonomy is not registered for this post type, show a message
      if (!in_array($taxonomy, $object_taxonomies)) {
        // Try to get terms anyway (might be a custom taxonomy)
        $terms = get_terms([
          'taxonomy'   => $taxonomy,
          'hide_empty' => false,
          'exclude'    => $exclude_ids,
        ]);
      }
    }

    // Additional filter: Remove "Uncategorized" by slug/name as fallback
    if (!is_wp_error($terms) && !empty($terms)) {
      $terms = array_filter($terms, function ($term) {
        // Exclude if slug is "uncategorized" or name is "Uncategorized"
        return strtolower($term->slug) !== 'uncategorized' &&
          strtolower($term->name) !== 'uncategorized';
      });
      // Re-index array after filtering
      $terms = array_values($terms);
    }

    // Build query args
    $query_args = [
      'post_type'      => $post_type,
      'posts_per_page' => $posts_per_page,
      'paged'           => $paged,
      'post_status'    => 'publish',
    ];

    // Add taxonomy filter if category is selected
    if ($selected_category > 0) {
      $query_args['tax_query'] = [
        [
          'taxonomy' => $taxonomy,
          'field'    => 'term_id',
          'terms'    => $selected_category,
        ],
      ];
    }

    // Execute query
    $query = new WP_Query($query_args);

    // Add custom class to root element
    if (isset($this->attributes['_root']['class'])) {
      $existing_classes = $this->attributes['_root']['class'];
      if (!is_array($existing_classes)) {
        $existing_classes = [$existing_classes];
      }
      if (!in_array('filtered-posts-wrapper', $existing_classes)) {
        $existing_classes[] = 'filtered-posts-wrapper';
      }
      $this->attributes['_root']['class'] = $existing_classes;
    } else {
      $this->set_attribute('_root', 'class', 'filtered-posts-wrapper');
    }

    // Prepare settings for AJAX
    $ajax_settings = [
      'showImage' => $show_image,
      'imageSize' => $image_size,
      'showCategory' => $show_category,
      'categoryLabel' => $category_label,
      'showExcerpt' => $show_excerpt,
      'excerptLength' => $excerpt_length,
      'readMoreText' => $read_more_text,
      'showPagination' => $show_pagination,
      'prevText' => $prev_text,
      'nextText' => $next_text,
    ];

    $output = "<div {$this->render_attributes('_root')} data-instance-id=\"{$unique_id}\" data-post-type=\"{$post_type}\" data-taxonomy=\"{$taxonomy}\" data-posts-per-page=\"{$posts_per_page}\" data-settings='" . esc_attr(wp_json_encode($ajax_settings)) . "'>";

    // Main container
    $output .= '<div class="filtered-posts-container">';

    // Sidebar with filters
    $output .= '<div class="filtered-posts-sidebar">';
    $output .= '<h3 class="filter-title">' . esc_html($filter_title) . '</h3>';
    $output .= '<ul class="filter-list">';

    // View All option
    if ($show_view_all) {
      $active_class = $selected_category === 0 ? ' active' : '';
      $output .= '<li class="filter-item' . esc_attr($active_class) . '" data-category="0">';
      $output .= '<span class="filter-text">' . esc_html($view_all_text) . '</span>';
      $output .= '<svg class="filter-arrow" width="16" height="27" viewBox="0 0 16 27" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M2.36667 26.6667L0 24.3L10.9667 13.3333L0 2.36667L2.36667 0L15.7 13.3333L2.36667 26.6667Z" fill="currentColor"/></svg>';
      $output .= '</li>';
    }

    // Category filters
    if (!is_wp_error($terms) && !empty($terms)) {
      foreach ($terms as $term) {
        $active_class = $selected_category === $term->term_id ? ' active' : '';
        $output .= '<li class="filter-item' . esc_attr($active_class) . '" data-category="' . esc_attr($term->term_id) . '">';
        $output .= '<span class="filter-text">' . esc_html($term->name) . '</span>';
        $output .= '</li>';
      }
    } else {
      // Debug: Show message if no terms found
      if (is_wp_error($terms)) {
        $output .= '<li class="filter-error">';
        $output .= '<span class="filter-text">' . esc_html__('Error loading terms: ', 'bricks') . esc_html($terms->get_error_message()) . '</span>';
        $output .= '</li>';
      } else {
        $output .= '<li class="filter-error">';
        $output .= '<span class="filter-text">' . esc_html__('No categories found. Please check that:', 'bricks') . '</span>';
        $output .= '<ul style="margin-top: 8px; padding-left: 20px; font-size: 12px;">';
        $output .= '<li>' . esc_html__('1. The taxonomy is correctly selected', 'bricks') . '</li>';
        $output .= '<li>' . esc_html__('2. Categories exist in WordPress', 'bricks') . '</li>';
        $output .= '<li>' . esc_html__('3. The taxonomy is registered for the selected post type', 'bricks') . '</li>';
        $output .= '</ul>';
        $output .= '</li>';
      }
    }

    $output .= '</ul>';
    $output .= '</div>'; // .filtered-posts-sidebar

    // Main content area
    $output .= '<div class="filtered-posts-content">';

    // Posts grid
    $output .= '<div class="filtered-posts-grid">';

    if ($query->have_posts()) {
      while ($query->have_posts()) {
        $query->the_post();
        $post_id = get_the_ID();

        $output .= '<article class="post-card">';

        // Featured image
        if ($show_image && has_post_thumbnail($post_id)) {
          $image_url = get_the_post_thumbnail_url($post_id, $image_size);
          $image_alt = get_post_meta(get_post_thumbnail_id($post_id), '_wp_attachment_image_alt', true) ?: get_the_title();

          $output .= '<div class="post-card-image-wrapper">';
          $output .= '<img src="' . esc_url($image_url) . '" alt="' . esc_attr($image_alt) . '" class="post-card-image">';
          $output .= '</div>'; // .post-card-image-wrapper
        }

        // Card content
        $output .= '<div class="post-card-content">';

        // Category tag (before title)
        if ($show_category) {
          $post_terms = wp_get_post_terms($post_id, $taxonomy);

          if (!empty($post_terms) && !is_wp_error($post_terms)) {
            $first_term = $post_terms[0];

            // If user uses dynamic tag, respect it
            if (strpos($category_label, '{') !== false) {
              $category_display = wp_strip_all_tags(
                bricks_render_dynamic_data($category_label, $post_id, 'text')
              );
            } else {
              // Default: show actual term name
              $category_display = $first_term->name;
            }

            $output .= '<span class="post-card-category">'
              . esc_html($category_display)
              . '</span>';
          }
        }


        // Title
        $output .= '<h3 class="post-card-title">';
        $output .= '<a href="' . esc_url(get_permalink($post_id)) . '">' . esc_html(get_the_title()) . '</a>';
        $output .= '</h3>';

        // Excerpt
        if ($show_excerpt) {
          $excerpt = get_the_excerpt($post_id);
          if (empty($excerpt)) {
            $excerpt = wp_trim_words(get_the_content($post_id), $excerpt_length);
          } else {
            $excerpt = wp_trim_words($excerpt, $excerpt_length);
          }
          $output .= '<p class="post-card-excerpt">' . esc_html($excerpt) . '</p>';
        }

        // Read more link
        $output .= '<a href="' . esc_url(get_permalink($post_id)) . '" class="post-card-read-more">';
        $output .= esc_html($read_more_text);
        $output .= '<svg class="read-more-arrow" width="16" height="27" viewBox="0 0 16 27" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M2.36667 26.6667L0 24.3L10.9667 13.3333L0 2.36667L2.36667 0L15.7 13.3333L2.36667 26.6667Z" fill="currentColor"/></svg>';
        $output .= '</a>';

        $output .= '</div>'; // .post-card-content
        $output .= '</article>'; // .post-card
      }
      wp_reset_postdata();
    } else {
      $output .= '<p class="no-posts">' . esc_html__('No posts found.', 'bricks') . '</p>';
    }

    $output .= '</div>'; // .filtered-posts-grid

    // Pagination
    if ($show_pagination && $query->max_num_pages > 1) {
      $output .= '<div class="filtered-posts-pagination">';

      // Previous button
      if ($paged > 1) {
        $output .= '<button class="pagination-prev" data-page="' . esc_attr($paged - 1) . '">';
        $output .= '<svg width="16" height="27" viewBox="0 0 16 27" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M13.6333 0L16 2.36667L5.03333 13.3333L16 24.3L13.6333 26.6667L0.3 13.3333L13.6333 0Z" fill="currentColor"/></svg>';
        $output .= esc_html($prev_text);
        $output .= '</button>';
      } else {
        $output .= '<button class="pagination-prev disabled" disabled>';
        $output .= '<svg width="16" height="27" viewBox="0 0 16 27" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M13.6333 0L16 2.36667L5.03333 13.3333L16 24.3L13.6333 26.6667L0.3 13.3333L13.6333 0Z" fill="currentColor"/></svg>';
        $output .= esc_html($prev_text);
        $output .= '</button>';
      }

      // Page numbers (centered)
      $output .= '<div class="pagination-numbers">';
      $max_pages = $query->max_num_pages;
      $range = 2; // Number of pages to show on each side of current page

      // Calculate start and end page numbers
      $start = max(1, $paged - $range);
      $end = min($max_pages, $paged + $range);

      // Show first page if not in range
      if ($start > 1) {
        $output .= '<button class="pagination-number" data-page="1">1</button>';
        if ($start > 2) {
          $output .= '<span class="pagination-dots">...</span>';
        }
      }

      // Show page numbers in range
      for ($i = $start; $i <= $end; $i++) {
        $active_class = ($i === $paged) ? ' active' : '';
        $output .= '<button class="pagination-number' . esc_attr($active_class) . '" data-page="' . esc_attr($i) . '">' . esc_html($i) . '</button>';
      }

      // Show last page if not in range
      if ($end < $max_pages) {
        if ($end < $max_pages - 1) {
          $output .= '<span class="pagination-dots">...</span>';
        }
        $output .= '<button class="pagination-number" data-page="' . esc_attr($max_pages) . '">' . esc_html($max_pages) . '</button>';
      }

      $output .= '</div>'; // .pagination-numbers

      // Next button
      if ($paged < $query->max_num_pages) {
        $output .= '<button class="pagination-next" data-page="' . esc_attr($paged + 1) . '">';
        $output .= esc_html($next_text);
        $output .= '<svg width="16" height="27" viewBox="0 0 16 27" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M2.36667 26.6667L0 24.3L10.9667 13.3333L0 2.36667L2.36667 0L15.7 13.3333L2.36667 26.6667Z" fill="currentColor"/></svg>';
        $output .= '</button>';
      } else {
        $output .= '<button class="pagination-next disabled" disabled>';
        $output .= esc_html($next_text);
        $output .= '<svg width="16" height="27" viewBox="0 0 16 27" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M2.36667 26.6667L0 24.3L10.9667 13.3333L0 2.36667L2.36667 0L15.7 13.3333L2.36667 26.6667Z" fill="currentColor"/></svg>';
        $output .= '</button>';
      }

      $output .= '</div>'; // .filtered-posts-pagination
    }

    $output .= '</div>'; // .filtered-posts-content
    $output .= '</div>'; // .filtered-posts-container
    $output .= '</div>'; // .filtered-posts-wrapper

    echo $output;
  }
}
