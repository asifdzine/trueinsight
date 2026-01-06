<?php
if (! defined('ABSPATH')) exit; // Exit if accessed directly

class Element_Image_Carousel extends \Bricks\Element
{
    public $category     = 'custom';
    public $name         = 'image-carousel';
    public $icon         = 'fas fa-images';
    public $scripts      = ['image-carousel-script'];

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
        wp_enqueue_script('image-carousel-script');
    }

    public function get_label()
    {
        return esc_html__('Image Carousel', 'bricks');
    }

    public function set_control_groups()
    {
        $this->control_groups['images'] = [
            'title' => esc_html__('Images', 'bricks'),
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

        $this->control_groups['imageStyle'] = [
            'title' => esc_html__('Image Style', 'bricks'),
            'tab'   => 'style',
        ];
    }

    public function set_controls()
    {
        // IMAGES CONTROLS
        $this->controls['images'] = [
            'tab'            => 'content',
            'group'          => 'images',
            'label'          => esc_html__('Images', 'bricks'),
            'type'           => 'repeater',
            'titleProperty' => 'image',
            'placeholder'    => esc_html__('Image', 'bricks'),
            'fields'         => [
                'image' => [
                    'label' => esc_html__('Image', 'bricks'),
                    'type'  => 'image',
                ],
            ],
        ];

        $this->controls['imageSize'] = [
            'tab'         => 'content',
            'group'       => 'images',
            'label'       => esc_html__('Image Size', 'bricks'),
            'type'        => 'select',
            'options'     => $this->get_image_sizes(),
            'default'     => 'large',
        ];

        $this->controls['imageLink'] = [
            'tab'         => 'content',
            'group'       => 'images',
            'label'       => esc_html__('Link Images', 'bricks'),
            'type'        => 'checkbox',
            'default'     => false,
            'description' => esc_html__('Make images clickable', 'bricks'),
        ];

        // NAVIGATION CONTROLS
        $this->controls['showNavigation'] = [
            'tab'     => 'content',
            'group'   => 'navigation',
            'label'   => esc_html__('Show Navigation Arrows', 'bricks'),
            'type'    => 'checkbox',
            'default' => true,
        ];

        $this->controls['showPagination'] = [
            'tab'     => 'content',
            'group'   => 'navigation',
            'label'   => esc_html__('Show Pagination Dots', 'bricks'),
            'type'    => 'checkbox',
            'default' => false,
        ];

        $this->controls['autoplay'] = [
            'tab'         => 'content',
            'group'       => 'navigation',
            'label'       => esc_html__('Autoplay', 'bricks'),
            'type'        => 'checkbox',
            'default'     => false,
            'description' => esc_html__('Automatically slide through images', 'bricks'),
        ];

        $this->controls['autoplayDelay'] = [
            'tab'         => 'content',
            'group'       => 'navigation',
            'label'       => esc_html__('Autoplay Delay (ms)', 'bricks'),
            'type'        => 'number',
            'min'         => 1000,
            'max'         => 10000,
            'step'        => 500,
            'default'     => 3000,
            'required'    => ['autoplay', '=', true],
        ];

        // LAYOUT STYLE CONTROLS
        $this->controls['gap'] = [
            'tab'     => 'style',
            'group'   => 'layout',
            'label'   => esc_html__('Slide Gap', 'bricks'),
            'type'    => 'number',
            'units'   => true,
            'css'     => [
                [
                    'property' => '--swiper-slide-gap',
                    'selector' => '.image-carousel-swiper',
                ],
            ],
            'default' => '48px',
        ];


        // IMAGE STYLE CONTROLS
        $this->controls['imageBorderRadius'] = [
            'tab'     => 'style',
            'group'   => 'imageStyle',
            'label'   => esc_html__('Image Border Radius', 'bricks'),
            'type'    => 'dimensions',
            'css'     => [
                [
                    'property' => 'border-radius',
                    'selector' => '.image-carousel-slide img',
                ],
            ],
        ];

        $this->controls['imageObjectFit'] = [
            'tab'     => 'style',
            'group'   => 'imageStyle',
            'label'   => esc_html__('Image Object Fit', 'bricks'),
            'type'    => 'select',
            'options' => [
                'contain'   => esc_html__('Contain (Show Full Image)', 'bricks'),
                'cover'     => esc_html__('Cover (Fill Container)', 'bricks'),
                'fill'      => esc_html__('Fill', 'bricks'),
                'none'      => esc_html__('None', 'bricks'),
                'scale-down' => esc_html__('Scale Down', 'bricks'),
            ],
            'default' => 'contain',
            'css'     => [
                [
                    'property' => 'object-fit',
                    'selector' => '.image-carousel-slide img',
                ],
            ],
        ];
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
        $images = !empty($settings['images']) ? $settings['images'] : [];
        $image_size = !empty($settings['imageSize']) ? $settings['imageSize'] : 'large';
        $image_link = isset($settings['imageLink']) ? $settings['imageLink'] : false;
        $show_navigation = isset($settings['showNavigation']) ? $settings['showNavigation'] : false;
        $show_pagination = isset($settings['showPagination']) ? $settings['showPagination'] : false;
        $autoplay = isset($settings['autoplay']) ? $settings['autoplay'] : false;
        $autoplay_delay = !empty($settings['autoplayDelay']) ? intval($settings['autoplayDelay']) : 3000;

        // Add custom class to root element
        if (isset($this->attributes['_root']['class'])) {
            $existing_classes = $this->attributes['_root']['class'];
            if (!is_array($existing_classes)) {
                $existing_classes = [$existing_classes];
            }
            if (!in_array('image-carousel-wrapper', $existing_classes)) {
                $existing_classes[] = 'image-carousel-wrapper';
            }
            $this->attributes['_root']['class'] = $existing_classes;
        } else {
            $this->set_attribute('_root', 'class', 'image-carousel-wrapper');
        }

        $output = "<div {$this->render_attributes('_root')} data-instance-id=\"{$unique_id}\" data-autoplay=\"" . ($autoplay ? 'true' : 'false') . "\" data-autoplay-delay=\"{$autoplay_delay}\">";

        // Carousel container (Swiper structure)
        $output .= '<div class="image-carousel-container">';
        $output .= '<div class="swiper image-carousel-swiper">';
        $output .= '<div class="swiper-wrapper">';

        if (!empty($images) && is_array($images)) {
            foreach ($images as $image_item) {
                // Handle image data - can be array, ID, or URL
                $image_id = 0;
                $image_url = '';
                
                if (is_array($image_item)) {
                    // Repeater field returns array with 'image' key
                    $image_data = !empty($image_item['image']) ? $image_item['image'] : '';
                    
                    if (is_array($image_data)) {
                        $image_id = !empty($image_data['id']) ? $image_data['id'] : 0;
                        $image_url = !empty($image_data['url']) ? $image_data['url'] : '';
                        if (!$image_url && $image_id) {
                            $image_url = wp_get_attachment_image_url($image_id, $image_size);
                        }
                    } elseif (is_numeric($image_data)) {
                        $image_id = $image_data;
                        $image_url = wp_get_attachment_image_url($image_id, $image_size);
                    } elseif (is_string($image_data)) {
                        $image_url = $image_data;
                    }
                } elseif (is_numeric($image_item)) {
                    // Direct ID (backward compatibility)
                    $image_id = $image_item;
                    $image_url = wp_get_attachment_image_url($image_id, $image_size);
                } elseif (is_string($image_item)) {
                    // Direct URL (backward compatibility)
                    $image_url = $image_item;
                }

                if ($image_url) {
                    $image_alt = $image_id ? get_post_meta($image_id, '_wp_attachment_image_alt', true) : '';
                    $image_full_url = $image_id ? wp_get_attachment_image_url($image_id, 'full') : $image_url;

                    $output .= '<div class="swiper-slide image-carousel-slide">';

                  

                    $output .= '<img src="' . esc_url($image_url) . '" alt="' . esc_attr($image_alt) . '" class="image-carousel-image">';

                   

                    $output .= '</div>'; // .swiper-slide
                }
            }
        } else {
            $output .= '<div class="swiper-slide image-carousel-slide">';
            $output .= '<p class="no-images">' . esc_html__('No images selected.', 'bricks') . '</p>';
            $output .= '</div>';
        }

        $output .= '</div>'; // .swiper-wrapper

        // Pagination
        if ($show_pagination) {
            $output .= '<div class="swiper-pagination"></div>';
        }

        $output .= '</div>'; // .swiper

        // Navigation buttons
        if ($show_navigation) {
            $output .= '<div class="image-carousel-nav-button image-carousel-prev swiper-button-prev" aria-label="' . esc_attr__('Previous', 'bricks') . '"></div>';
            $output .= '<div class="image-carousel-nav-button image-carousel-next swiper-button-next" aria-label="' . esc_attr__('Next', 'bricks') . '"></div>';
        }

        $output .= '</div>'; // .image-carousel-container
        $output .= '</div>'; // .image-carousel-wrapper

        echo $output;
    }
}

