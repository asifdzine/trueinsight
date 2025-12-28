<?php
if (! defined('ABSPATH')) exit; // Exit if accessed directly

class Element_Our_Services extends \Bricks\Element
{
  public $category     = 'custom';
  public $name         = 'our-services';
  public $icon         = 'fas fa-list-alt';
  public $scripts      = ['our-services-script'];

  public function get_label()
  {
    return esc_html__('Our Services', 'bricks');
  }

  public function set_control_groups()
  {
    $this->control_groups['heading'] = [
      'title' => esc_html__('Heading', 'bricks'),
      'tab'   => 'content',
    ];

    $this->control_groups['image'] = [
      'title' => esc_html__('Image', 'bricks'),
      'tab'   => 'content',
    ];

    $this->control_groups['services'] = [
      'title' => esc_html__('Services', 'bricks'),
      'tab'   => 'content',
    ];

    $this->control_groups['layout'] = [
      'title' => esc_html__('Layout', 'bricks'),
      'tab'   => 'style',
    ];
  }

  public function set_controls()
  {
    // Heading
    $this->controls['heading'] = [
      'tab'            => 'content',
      'group'          => 'heading',
      'label'          => esc_html__('Heading', 'bricks'),
      'type'           => 'text',
      'hasDynamicData' => 'text',
      'default'        => esc_html__('Our Services', 'bricks'),
    ];

    $this->controls['headingTypography'] = [
      'tab'     => 'content',
      'group'   => 'heading',
      'label'   => esc_html__('Typography', 'bricks'),
      'type'    => 'typography',
      'css'     => [
        [
          'property' => 'typography',
          'selector' => '.services-heading',
        ],
      ],
    ];

    // Services Repeater
    $this->controls['services'] = [
      'tab'            => 'content',
      'group'          => 'services',
      'label'          => esc_html__('Services', 'bricks'),
      'type'           => 'repeater',
      'titleProperty'  => 'title',
      'placeholder'    => esc_html__('Service', 'bricks'),
      'fields'         => [
        'title' => [
          'label'       => esc_html__('Service Title', 'bricks'),
          'type'        => 'text',
          'default'     => esc_html__('Service Title', 'bricks'),
        ],
        'subtitle' => [
          'label'       => esc_html__('Service Subtitle', 'bricks'),
          'type'        => 'text',
          'default'     => '',
          'description' => esc_html__('Small text that appears under the service title', 'bricks'),
        ],
        'description' => [
          'label'       => esc_html__('Service Description', 'bricks'),
          'type'        => 'textarea',
          'default'     => '',
          'description' => esc_html__('Description that appears under the image when service is clicked', 'bricks'),
        ],
        'image' => [
          'label'       => esc_html__('Service Image', 'bricks'),
          'type'        => 'image',
        ],
      ],
    ];

    // Default/First Image
    $this->controls['defaultImage'] = [
      'tab'            => 'content',
      'group'          => 'image',
      'label'          => esc_html__('Default Image', 'bricks'),
      'type'           => 'image',
      'description'    => esc_html__('Image shown by default. If not set, first service image will be used.', 'bricks'),
    ];

    $this->controls['imageBorderRadius'] = [
      'tab'     => 'style',
      'group'   => 'image',
      'label'   => esc_html__('Border Radius', 'bricks'),
      'type'    => 'dimensions',
      'css'     => [
        [
          'property' => 'border-radius',
          'selector' => '.service-image-wrapper img',
        ],
      ],
    ];

    // Service List Styling
    $this->controls['serviceItemPadding'] = [
      'tab'     => 'style',
      'group'   => 'services',
      'label'   => esc_html__('Item Padding', 'bricks'),
      'type'    => 'spacing',
      'css'     => [
        [
          'property' => 'padding',
          'selector' => '.service-item',
        ],
      ],
    ];

    $this->controls['serviceItemBorder'] = [
      'tab'     => 'style',
      'group'   => 'services',
      'label'   => esc_html__('Item Border', 'bricks'),
      'type'    => 'border',
      'css'     => [
        [
          'property' => 'border',
          'selector' => '.service-item',
        ],
      ],
    ];

    $this->controls['serviceItemActiveBackground'] = [
      'tab'     => 'style',
      'group'   => 'services',
      'label'   => esc_html__('Active Item Background', 'bricks'),
      'type'    => 'color',
      'css'     => [
        [
          'property' => 'background-color',
          'selector' => '.service-item.active',
        ],
      ],
    ];

    $this->controls['serviceItemTypography'] = [
      'tab'     => 'style',
      'group'   => 'services',
      'label'   => esc_html__('Typography', 'bricks'),
      'type'    => 'typography',
      'css'     => [
        [
          'property' => 'typography',
          'selector' => '.service-item',
        ],
      ],
    ];

    $this->controls['serviceSubtitleTypography'] = [
      'tab'     => 'style',
      'group'   => 'services',
      'label'   => esc_html__('Subtitle Typography', 'bricks'),
      'type'    => 'typography',
      'css'     => [
        [
          'property' => 'typography',
          'selector' => '.service-subtitle',
        ],
      ],
    ];

    $this->controls['serviceDescriptionTypography'] = [
      'tab'     => 'style',
      'group'   => 'services',
      'label'   => esc_html__('Description Typography', 'bricks'),
      'type'    => 'typography',
      'css'     => [
        [
          'property' => 'typography',
          'selector' => '.service-description',
        ],
      ],
    ];

    $this->controls['serviceDescriptionPadding'] = [
      'tab'     => 'style',
      'group'   => 'services',
      'label'   => esc_html__('Description Padding', 'bricks'),
      'type'    => 'spacing',
      'css'     => [
        [
          'property' => 'padding',
          'selector' => '.service-description',
        ],
      ],
    ];

    $this->controls['serviceDescriptionBackground'] = [
      'tab'     => 'style',
      'group'   => 'services',
      'label'   => esc_html__('Description Background', 'bricks'),
      'type'    => 'color',
      'css'     => [
        [
          'property' => 'background-color',
          'selector' => '.service-description',
        ],
      ],
    ];

    // Layout
    $this->controls['layoutGap'] = [
      'tab'     => 'style',
      'group'   => 'layout',
      'label'   => esc_html__('Gap', 'bricks'),
      'type'    => 'number',
      'units'   => true,
      'css'     => [
        [
          'property' => 'gap',
          'selector' => '.our-services-wrapper',
        ],
      ],
    ];

    $this->controls['flipLayout'] = [
      'tab'         => 'style',
      'group'       => 'layout',
      'label'       => esc_html__('Flip Layout', 'bricks'),
      'type'        => 'checkbox',
      'default'     => false,
      'description' => esc_html__('If enabled, image will be on the right and services list on the left (desktop only)', 'bricks'),
    ];

    $this->controls['imageWidth'] = [
      'tab'     => 'style',
      'group'   => 'layout',
      'label'   => esc_html__('Image Width (Desktop)', 'bricks'),
      'type'    => 'number',
      'units'   => true,
      'css'     => [
        [
          'property' => 'width',
          'selector' => '.service-image-wrapper',
        ],
      ],
    ];

    $this->controls['servicesWidth'] = [
      'tab'     => 'style',
      'group'   => 'layout',
      'label'   => esc_html__('Services Width (Desktop)', 'bricks'),
      'type'    => 'number',
      'units'   => true,
      'css'     => [
        [
          'property' => 'width',
          'selector' => '.services-list',
        ],
      ],
    ];
  }

  /**
   * Enqueue scripts
   */
  public function enqueue_scripts()
  {
    wp_enqueue_script('our-services-script');
  }

  /** 
   * Render element HTML on frontend
   */
  public function render()
  {
    $settings = $this->settings;
    $heading  = ! empty($settings['heading']) ? $settings['heading'] : '';
    $services = ! empty($settings['services']) ? $settings['services'] : [];
    $default_image = ! empty($settings['defaultImage']) ? $settings['defaultImage'] : '';
    $flip_layout = isset($settings['flipLayout']) ? $settings['flipLayout'] : false;

    // Return element placeholder if no services
    if (empty($services) || ! is_array($services) || count($services) === 0) {
      return $this->render_element_placeholder([
        'icon-class'  => 'ti-list',
        'title'       => esc_html__('Please add services.', 'bricks'),
        'description' => esc_html__('Add at least one service to display.', 'bricks'),
      ]);
    }

    // Determine which image to show initially
    $active_image = $default_image;
    $active_service_index = -1;

    // Helper function to normalize image data for comparison
    $normalize_image = function ($img) {
      if (is_array($img)) {
        return ! empty($img['id']) ? $img['id'] : (! empty($img['url']) ? $img['url'] : '');
      }
      return $img;
    };

    // If no default image, use first service image
    if (empty($active_image) && ! empty($services[0]['image'])) {
      $active_image = $services[0]['image'];
      $active_service_index = 0;
    } else if (! empty($default_image)) {
      $normalized_default = $normalize_image($default_image);
      // Find which service matches the default image
      foreach ($services as $index => $service) {
        if (! empty($service['image'])) {
          $normalized_service = $normalize_image($service['image']);
          if (
            $normalized_service === $normalized_default ||
            (is_numeric($normalized_service) && is_numeric($normalized_default) && intval($normalized_service) === intval($normalized_default))
          ) {
            $active_service_index = $index;
            break;
          }
        }
      }
      // If no match found, default to first service
      if ($active_service_index === -1 && ! empty($services[0]['image'])) {
        $active_image = $services[0]['image'];
        $active_service_index = 0;
      }
    }

    // Generate unique ID for this instance
    $unique_id = $this->id;

    /**
     * '_root' attribute contains element ID, classes, etc. 
     * Bricks automatically adds classes like 'brxe-our-services' and the element ID
     * We add our custom class for CSS targeting
     */
    // Add custom class to existing classes array
    if (isset($this->attributes['_root']['class'])) {
      $existing_classes = $this->attributes['_root']['class'];
      if (! is_array($existing_classes)) {
        $existing_classes = [$existing_classes];
      }
      if (! in_array('our-services-wrapper', $existing_classes)) {
        $existing_classes[] = 'our-services-wrapper';
      }
      // Add flip layout class if enabled
      if ($flip_layout && ! in_array('layout-flipped', $existing_classes)) {
        $existing_classes[] = 'layout-flipped';
      }
      $this->attributes['_root']['class'] = $existing_classes;
    } else {
      $classes = ['our-services-wrapper'];
      if ($flip_layout) {
        $classes[] = 'layout-flipped';
      }
      $this->set_attribute('_root', 'class', implode(' ', $classes));
    }

    $output = "<div {$this->render_attributes('_root')} data-instance-id=\"{$unique_id}\">";

    // Heading (appears first on mobile, before services-content-wrapper)


    // Main wrapper for image and services list
    $output .= '<div class="services-content-wrapper">';

    // Image wrapper (mobile: below services list, desktop: left side)
    $output .= '<div class="service-image-wrapper">';
    if ($active_image) {
      // Handle image data - can be array, ID, or URL
      $image_url = '';
      $image_id = 0;

      if (is_array($active_image)) {
        // Array format: ['id' => 123, 'url' => '...']
        $image_id = ! empty($active_image['id']) ? $active_image['id'] : 0;
        $image_url = ! empty($active_image['url']) ? $active_image['url'] : '';
        if (! $image_url && $image_id) {
          $image_url = wp_get_attachment_image_url($image_id, 'full');
        }
      } elseif (is_numeric($active_image)) {
        // Just an ID
        $image_id = $active_image;
        $image_url = wp_get_attachment_image_url($image_id, 'full');
      } else {
        // URL string
        $image_url = $active_image;
      }

      if ($image_url) {
        $image_alt = $image_id ? get_post_meta($image_id, '_wp_attachment_image_alt', true) : '';
        $image_alt = $image_alt ? $image_alt : ($heading ? $heading : 'Service Image');
        $output .= '<img src="' . esc_url($image_url) . '" alt="' . esc_attr($image_alt) . '" class="service-main-image">';
      }
    }

    // Description container (shown on click)
    $active_description = '';
    $is_description_active = false;
    if ($active_service_index >= 0 && isset($services[$active_service_index]['description'])) {
      $active_description = $services[$active_service_index]['description'];
      $is_description_active = !empty($active_description);
    }

    // Always create description container (hidden by default if no description)
    $description_class = $is_description_active ? 'service-description active' : 'service-description';
    $output .= '<div class="' . esc_attr($description_class) . '" data-service-index="' . esc_attr($active_service_index >= 0 ? $active_service_index : '') . '">';
    $output .= '<div class="service-description-content">';
    if ($active_description) {
      $output .= wp_kses_post($active_description);
    }
    $output .= '</div>'; // .service-description-content
    $output .= '</div>'; // .service-description

    $output .= '</div>'; // .service-image-wrapper

    // Services list
    $output .= '<div class="services-list">';


    if ($heading) {
      $this->set_attribute('heading', 'class', 'services-heading');
      $output .= "<h2 {$this->render_attributes('heading')}>" . esc_html($heading) . "</h2>";
    }

    foreach ($services as $index => $service) {
      $service_title = ! empty($service['title']) ? $service['title'] : '';
      $service_subtitle = ! empty($service['subtitle']) ? $service['subtitle'] : '';
      $service_description = ! empty($service['description']) ? $service['description'] : '';
      $service_image = ! empty($service['image']) ? $service['image'] : '';

      if (! $service_title) {
        continue;
      }

      // Determine if this service should be active
      $is_active = ($active_service_index === $index);
      $active_class = $is_active ? ' active' : '';

      $service_image_url = '';
      if ($service_image) {
        // Handle image data - can be array, ID, or URL
        if (is_array($service_image)) {
          // Array format: ['id' => 123, 'url' => '...']
          $service_image_id = ! empty($service_image['id']) ? $service_image['id'] : 0;
          $service_image_url = ! empty($service_image['url']) ? $service_image['url'] : '';
          if (! $service_image_url && $service_image_id) {
            $service_image_url = wp_get_attachment_image_url($service_image_id, 'full');
          }
        } elseif (is_numeric($service_image)) {
          // Just an ID
          $service_image_url = wp_get_attachment_image_url($service_image, 'full');
        } else {
          // URL string
          $service_image_url = $service_image;
        }
      }

      $output .= '<div class="service-item' . esc_attr($active_class) . '" 
                        data-service-index="' . esc_attr($index) . '"';

      if ($service_image_url) {
        $output .= ' data-image-url="' . esc_url($service_image_url) . '"';
      }

      if ($service_description) {
        // Store description HTML in a data attribute (will be used by JS)
        $output .= ' data-description-html="' . esc_attr(base64_encode($service_description)) . '"';
      }

      $output .= '>';

      // Service number
      $service_number = str_pad($index + 1, 2, '0', STR_PAD_LEFT);
      $output .= '<span class="service-number">' . esc_html($service_number) . '</span>';

      // Service title and subtitle wrapper
      $output .= '<div class="service-title-wrapper">';

      // Service title
      $output .= '<span class="service-title">' . esc_html($service_title) . '</span>';

      // Service subtitle (small text under title)
      if ($service_subtitle) {
        $output .= '<span class="service-subtitle">' . esc_html($service_subtitle) . '</span>';
      }

      $output .= '</div>'; // .service-title-wrapper

      // Arrow icon
      $output .= '<span class="service-arrow">
        <svg width="16" height="27" viewBox="0 0 16 27" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M2.36667 26.6667L0 24.3L10.9667 13.3333L0 2.36667L2.36667 0L15.7 13.3333L2.36667 26.6667Z" fill="#003550"/>
        </svg>
      </span>';

      $output .= '</div>'; // .service-item

      // Mobile image container (hidden by default, shown via JavaScript when service is active)
      if ($service_image_url) {
        $image_id = 0;
        if (is_array($service_image)) {
          $image_id = ! empty($service_image['id']) ? $service_image['id'] : 0;
        } elseif (is_numeric($service_image)) {
          $image_id = $service_image;
        }

        $image_alt = $image_id ? get_post_meta($image_id, '_wp_attachment_image_alt', true) : '';
        $image_alt = $image_alt ? $image_alt : ($heading ? $heading : 'Service Image');

        $mobile_image_class = $is_active ? 'service-image-mobile active' : 'service-image-mobile';
        $mobile_image_style = $is_active ? '' : 'display: none;';

        $output .= '<div class="' . esc_attr($mobile_image_class) . '" 
                          data-service-index="' . esc_attr($index) . '"
                          style="' . esc_attr($mobile_image_style) . '">';
        $output .= '<img src="' . esc_url($service_image_url) . '" 
                        alt="' . esc_attr($image_alt) . '" 
                        class="service-main-image-mobile">';
        $output .= '</div>'; // .service-image-mobile
      }
    }
    $output .= '</div>'; // .services-list

    $output .= '</div>'; // .services-content-wrapper

    $output .= '</div>'; // .our-services-wrapper

    // Output final element HTML
    echo $output;
  }
}
