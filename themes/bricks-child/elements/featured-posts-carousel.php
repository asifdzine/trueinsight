<?php
if (! defined('ABSPATH')) exit; // Exit if accessed directly

class Element_Featured_Posts_Carousel extends \Bricks\Element
{
    public $category     = 'custom';
    public $name         = 'featured-posts-carousel';
    public $icon         = 'fas fa-sliders-h';
    public $scripts      = ['featured-posts-carousel-script'];

    public function enqueue_scripts()
    {
        // Enqueue Swiper library from local files
        $swiper_css_path = get_stylesheet_directory() . '/css/swiper-bundle.min.css';
        $swiper_js_path = get_stylesheet_directory() . '/js/swiper-bundle.min.js';

        if (file_exists($swiper_css_path)) {
            wp_enqueue_style(
                'swiper',
                get_stylesheet_directory_uri() . '/css/swiper-bundle.min.css',
                [],
                filemtime($swiper_css_path)
            );
        }

        if (file_exists($swiper_js_path)) {
            wp_enqueue_script(
                'swiper',
                get_stylesheet_directory_uri() . '/js/swiper-bundle.min.js',
                [],
                filemtime($swiper_js_path),
                true
            );
        }

        // Enqueue our custom script
        wp_enqueue_script('featured-posts-carousel-script');
    }

    public function get_label()
    {
        return esc_html__('Featured Posts Carousel', 'bricks');
    }

    public function set_control_groups()
    {
        $this->control_groups['query'] = [
            'title' => esc_html__('Query', 'bricks'),
            'tab'   => 'content',
        ];

        $this->control_groups['header'] = [
            'title' => esc_html__('Header', 'bricks'),
            'tab'   => 'content',
        ];

        $this->control_groups['card'] = [
            'title' => esc_html__('Card', 'bricks'),
            'tab'   => 'content',
        ];

        $this->control_groups['navigation'] = [
            'title' => esc_html__('Navigation', 'bricks'),
            'tab'   => 'content',
        ];

        $this->control_groups['layout'] = [
            'title' => esc_html__('Layout', 'bricks'),
            'tab'   => 'style',
        ];

        $this->control_groups['headerStyle'] = [
            'title' => esc_html__('Header Style', 'bricks'),
            'tab'   => 'style',
        ];

        $this->control_groups['cardStyle'] = [
            'title' => esc_html__('Card Style', 'bricks'),
            'tab'   => 'style',
        ];

        $this->control_groups['navigationStyle'] = [
            'title' => esc_html__('Navigation Style', 'bricks'),
            'tab'   => 'style',
        ];
    }

    public function set_controls()
    {
        // QUERY CONTROLS
        $this->controls['displayType'] = [
            'tab'     => 'content',
            'group'   => 'query',
            'label'   => esc_html__('Display Type', 'bricks'),
            'type'    => 'select',
            'options' => [
                'featured' => esc_html__('Featured Posts', 'bricks'),
                'related'  => esc_html__('Related Posts', 'bricks'),
            ],
            'default' => 'featured',
        ];

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
            'label'       => esc_html__('Number of Posts', 'bricks'),
            'type'        => 'number',
            'min'         => 1,
            'max'         => 50,
            'default'     => 6,
            'placeholder' => 6,
        ];

        $this->controls['postsToShow'] = [
            'tab'         => 'content',
            'group'       => 'query',
            'label'       => esc_html__('Posts Visible at Once', 'bricks'),
            'type'        => 'number',
            'min'         => 1,
            'max'         => 6,
            'default'     => 2,
            'placeholder' => 2,
            'description' => esc_html__('Number of posts visible in the carousel at once', 'bricks'),
        ];

        $this->controls['taxonomy'] = [
            'tab'         => 'content',
            'group'       => 'query',
            'label'       => esc_html__('Taxonomy for Category Tag', 'bricks'),
            'type'        => 'select',
            'options'     => $this->get_taxonomies(),
            'placeholder' => esc_html__('category', 'bricks'),
            'default'     => 'category',
        ];

        $this->controls['featuredTag'] = [
            'tab'         => 'content',
            'group'       => 'query',
            'label'       => esc_html__('Featured Tag/Category', 'bricks'),
            'type'        => 'text',
            'description' => esc_html__('Enter tag slug or category slug to filter featured posts. Leave empty to show all posts.', 'bricks'),
            'required'    => ['displayType', '=', 'featured'],
        ];

        $this->controls['relatedTaxonomy'] = [
            'tab'         => 'content',
            'group'       => 'query',
            'label'       => esc_html__('Related By Taxonomy', 'bricks'),
            'type'        => 'select',
            'options'     => $this->get_taxonomies(),
            'placeholder' => esc_html__('category', 'bricks'),
            'default'     => 'category',
            'description' => esc_html__('Show related posts based on this taxonomy', 'bricks'),
            'required'    => ['displayType', '=', 'related'],
        ];

        // HEADER CONTROLS
        $this->controls['title'] = [
            'tab'            => 'content',
            'group'          => 'header',
            'label'          => esc_html__('Title', 'bricks'),
            'type'           => 'text',
            'hasDynamicData' => 'text',
            'default'        => esc_html__('Featured Resources', 'bricks'),
        ];

        $this->controls['showNavigation'] = [
            'tab'     => 'content',
            'group'   => 'navigation',
            'label'   => esc_html__('Show Navigation Arrows', 'bricks'),
            'type'    => 'checkbox',
            'default' => true,
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
            'default'        => esc_html__('Type', 'bricks'),
            'required'       => ['showCategory', '=', true],
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
            'default'     => esc_html__('Know More', 'bricks'),
        ];

        // LAYOUT STYLE CONTROLS
        $this->controls['gap'] = [
            'tab'     => 'style',
            'group'   => 'layout',
            'label'   => esc_html__('Card Gap', 'bricks'),
            'type'    => 'number',
            'units'   => true,
            'css'     => [
                [
                    'property' => 'gap',
                    'selector' => '.featured-posts-carousel-slider',
                ],
            ],
            'default' => '30px',
        ];

        // HEADER STYLE CONTROLS
        $this->controls['titleTypography'] = [
            'tab'     => 'style',
            'group'   => 'headerStyle',
            'label'   => esc_html__('Title Typography', 'bricks'),
            'type'    => 'typography',
            'css'     => [
                [
                    'property' => 'typography',
                    'selector' => '.featured-posts-header h2',
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
                    'selector' => '.featured-post-card-title',
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
                    'selector' => '.featured-post-card-excerpt',
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
                    'selector' => '.featured-post-card-category',
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
                    'selector' => '.featured-post-card-read-more',
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
                    'selector' => '.featured-post-card',
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
                    'selector' => '.featured-post-card',
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
                    'selector' => '.featured-post-card',
                ],
            ],
        ];

        // NAVIGATION STYLE CONTROLS
        $this->controls['navButtonSize'] = [
            'tab'     => 'style',
            'group'   => 'navigationStyle',
            'label'   => esc_html__('Button Size', 'bricks'),
            'type'    => 'number',
            'units'   => true,
            'css'     => [
                [
                    'property' => 'width',
                    'selector' => '.featured-posts-nav-button',
                ],
                [
                    'property' => 'height',
                    'selector' => '.featured-posts-nav-button',
                ],
            ],
            'default' => '40px',
        ];

        $this->controls['navButtonBackground'] = [
            'tab'     => 'style',
            'group'   => 'navigationStyle',
            'label'   => esc_html__('Button Background', 'bricks'),
            'type'    => 'color',
            'css'     => [
                [
                    'property' => 'background-color',
                    'selector' => '.featured-posts-nav-button',
                ],
            ],
        ];

        $this->controls['navButtonBorderRadius'] = [
            'tab'     => 'style',
            'group'   => 'navigationStyle',
            'label'   => esc_html__('Button Border Radius', 'bricks'),
            'type'    => 'dimensions',
            'css'     => [
                [
                    'property' => 'border-radius',
                    'selector' => '.featured-posts-nav-button',
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
     * Render element HTML on frontend
     */
    public function render()
    {
        $settings = $this->settings;
        $unique_id = $this->id;

        // Get settings
        $display_type = !empty($settings['displayType']) ? $settings['displayType'] : 'featured';
        $post_type = !empty($settings['postType']) ? $settings['postType'] : 'post';
        $posts_per_page = !empty($settings['postsPerPage']) ? intval($settings['postsPerPage']) : 6;
        $posts_to_show = !empty($settings['postsToShow']) ? intval($settings['postsToShow']) : 2;
        $taxonomy = !empty($settings['taxonomy']) ? $settings['taxonomy'] : 'category';
        $title = !empty($settings['title']) ? $settings['title'] : esc_html__('Featured Resources', 'bricks');
        $show_navigation = isset($settings['showNavigation']) ? $settings['showNavigation'] : true;
        $show_image = isset($settings['showImage']) ? $settings['showImage'] : true;
        $image_size = !empty($settings['imageSize']) ? $settings['imageSize'] : 'large';
        $show_category = isset($settings['showCategory']) ? $settings['showCategory'] : true;
        $category_label = !empty($settings['categoryLabel']) ? $settings['categoryLabel'] : esc_html__('Type', 'bricks');
        $show_excerpt = isset($settings['showExcerpt']) ? $settings['showExcerpt'] : true;
        $excerpt_length = !empty($settings['excerptLength']) ? intval($settings['excerptLength']) : 100;
        $read_more_text = !empty($settings['readMoreText']) ? $settings['readMoreText'] : esc_html__('Know More', 'bricks');
        $featured_tag = !empty($settings['featuredTag']) ? $settings['featuredTag'] : '';
        $related_taxonomy = !empty($settings['relatedTaxonomy']) ? $settings['relatedTaxonomy'] : 'category';

        // Build query args
        $query_args = [
            'post_type'      => $post_type,
            'posts_per_page' => $posts_per_page,
            'post_status'    => 'publish',
        ];

        // Handle Featured Posts
        if ($display_type === 'featured') {
            if (!empty($featured_tag)) {
                // Check if it's a tag or category
                $tag_term = get_term_by('slug', $featured_tag, 'post_tag');
                $cat_term = get_term_by('slug', $featured_tag, 'category');

                if ($tag_term) {
                    $query_args['tag'] = $featured_tag;
                } elseif ($cat_term) {
                    $query_args['category_name'] = $featured_tag;
                } else {
                    // Try as tag first, then category
                    $query_args['tag'] = $featured_tag;
                }
            }
        }

        // Handle Related Posts
        if ($display_type === 'related') {
            $current_post_id = get_the_ID();

            if ($current_post_id) {
                // Exclude current post
                $query_args['post__not_in'] = [$current_post_id];

                // Get terms from current post
                $current_terms = wp_get_post_terms($current_post_id, $related_taxonomy);

                if (!empty($current_terms) && !is_wp_error($current_terms)) {
                    $term_ids = wp_list_pluck($current_terms, 'term_id');

                    // Query posts with same terms
                    $query_args['tax_query'] = [
                        [
                            'taxonomy' => $related_taxonomy,
                            'field'    => 'term_id',
                            'terms'    => $term_ids,
                        ],
                    ];
                } else {
                    // If no terms found, return empty query
                    $query_args['post__in'] = [0]; // No posts
                }
            } else {
                // If not on a single post page, return empty query
                $query_args['post__in'] = [0]; // No posts
            }
        }

        // Execute query
        $query = new WP_Query($query_args);

        // Add custom class to root element
        if (isset($this->attributes['_root']['class'])) {
            $existing_classes = $this->attributes['_root']['class'];
            if (!is_array($existing_classes)) {
                $existing_classes = [$existing_classes];
            }
            if (!in_array('featured-posts-carousel-wrapper', $existing_classes)) {
                $existing_classes[] = 'featured-posts-carousel-wrapper';
            }
            $this->attributes['_root']['class'] = $existing_classes;
        } else {
            $this->set_attribute('_root', 'class', 'featured-posts-carousel-wrapper');
        }

        $output = "<div {$this->render_attributes('_root')} data-instance-id=\"{$unique_id}\" data-posts-to-show=\"{$posts_to_show}\">";

        // Header with title and navigation
        $output .= '<div class="featured-posts-header">';
        $output .= '<h2 class="featured-posts-title">' . esc_html($title) . '</h2>';

        if ($show_navigation) {
            $output .= '<div class="featured-posts-navigation">';
            $output .= '<div class="featured-posts-nav-button featured-posts-prev swiper-button-prev" aria-label="' . esc_attr__('Previous', 'bricks') . '">';

            $output .= '</div>';
            $output .= '<div class="featured-posts-nav-button featured-posts-next swiper-button-next" aria-label="' . esc_attr__('Next', 'bricks') . '">';

            $output .= '</div>';
            $output .= '</div>'; // .featured-posts-navigation
        }

        $output .= '</div>'; // .featured-posts-header

        // Carousel container (Swiper structure)
        $output .= '<div class="featured-posts-carousel-container">';
        $output .= '<div class="swiper featured-posts-swiper" data-posts-to-show="' . esc_attr($posts_to_show) . '">';
        $output .= '<div class="swiper-wrapper">';

        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $post_id = get_the_ID();

                $output .= '<div class="swiper-slide">';
                $output .= '<article class="featured-post-card">';

                // Card content wrapper
                $output .= '<div class="featured-post-card-content">';

                // Featured image
                if ($show_image && has_post_thumbnail($post_id)) {
                    $image_url = get_the_post_thumbnail_url($post_id, $image_size);
                    $image_alt = get_post_meta(get_post_thumbnail_id($post_id), '_wp_attachment_image_alt', true) ?: get_the_title();

                    $output .= '<div class="featured-post-card-image-wrapper">';
                    $output .= '<img src="' . esc_url($image_url) . '" alt="' . esc_attr($image_alt) . '" class="featured-post-card-image">';
                    $output .= '</div>'; // .featured-post-card-image-wrapper
                }

                // Text content
                $output .= '<div class="featured-post-card-text">';

                // Category tag
                if ($show_category) {
                    $post_terms = wp_get_post_terms($post_id, $taxonomy);
                    if (!empty($post_terms) && !is_wp_error($post_terms)) {
                        $first_term = $post_terms[0];

                        // Render dynamic data in category label
                        $rendered_label = bricks_render_dynamic_data($category_label, $post_id, 'text');

                        // Determine what to display
                        if (strpos($category_label, '{') !== false) {
                            // Dynamic data tag was used
                            if ($rendered_label !== $category_label) {
                                // Dynamic data was successfully rendered
                                $category_display = wp_strip_all_tags($rendered_label);
                            } else {
                                // Dynamic data didn't render, fall back to term name
                                $category_display = $first_term->name;
                            }
                        } else {
                            // Static text was entered - use it as-is
                            $category_display = !empty($category_label) ? $category_label : $first_term->name;
                        }

                        // Ensure we have something to display
                        if (empty($category_display)) {
                            $category_display = $first_term->name;
                        }

                        $output .= '<span class="featured-post-card-category">' . esc_html($category_display) . '</span>';
                    }
                }

                // Title
                $output .= '<h3 class="featured-post-card-title">';
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
                    $output .= '<p class="featured-post-card-excerpt">' . esc_html($excerpt) . '</p>';
                }

                // Read more link
                $output .= '<a href="' . esc_url(get_permalink($post_id)) . '" class="featured-post-card-read-more">';
                $output .= esc_html($read_more_text);
                $output .= '<svg class="read-more-arrow" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6 4L10 8L6 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>';
                $output .= '</a>';

                $output .= '</div>'; // .featured-post-card-text
                $output .= '</div>'; // .featured-post-card-content
                $output .= '</article>'; // .featured-post-card
                $output .= '</div>'; // .swiper-slide
            }
            wp_reset_postdata();
        } else {
            $output .= '<p class="no-posts">' . esc_html__('No posts found.', 'bricks') . '</p>';
        }

        $output .= '</div>'; // .swiper-wrapper
        $output .= '</div>'; // .swiper
        $output .= '</div>'; // .featured-posts-carousel-container
        $output .= '</div>'; // .featured-posts-carousel-wrapper

        echo $output;
    }
}
