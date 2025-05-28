<?php
namespace AutoRide\Theme\Core;

class WidgetArea {
    private Validation $validation;
    private Helper $helper;

    public function __construct() {
        $this->validation = new Validation();
        $this->helper = new Helper();
    }

    public function getWidgetAreaByPost($post, string $option_name, bool $inherit = true): array {
        $widget_area = [
            'id' => 0,
            'location' => 0
        ];

        if ($inherit) {
            // Get widget area from theme options
            $widget_area['id'] = (int)ThemeSetup::getOption($option_name . '_id', 0);
            $widget_area['location'] = (int)ThemeSetup::getOption($option_name . '_location', 0);
        }

        // Get post-specific widget area if set
        $post_widget_area_id = get_post_meta($post->ID, '_' . $option_name . '_id', true);
        $post_widget_area_location = get_post_meta($post->ID, '_' . $option_name . '_location', true);

        if ($this->validation->isNumber($post_widget_area_id)) {
            $widget_area['id'] = (int)$post_widget_area_id;
        }

        if ($this->validation->isNumber($post_widget_area_location)) {
            $widget_area['location'] = (int)$post_widget_area_location;
        }

        return $widget_area;
    }

    public function getWidgetAreaCSSClass(array $widget_area): string {
        $classes = [];

        if (!isset($widget_area['location']) || !$this->validation->isNumber($widget_area['location'])) {
            return 'theme-page-sidebar-disable';
        }

        switch ($widget_area['location']) {
            case 1:
                $classes[] = 'theme-page-sidebar-left';
                break;
            case 2:
                $classes[] = 'theme-page-sidebar-right';
                break;
            default:
                $classes[] = 'theme-page-sidebar-disable';
        }

        return implode(' ', $classes);
    }

    public function render(array $widget_area): string {
        if (!isset($widget_area['id']) || !$this->validation->isNumber($widget_area['id'])) {
            return '';
        }

        ob_start();
        
        if (is_active_sidebar($widget_area['id'])) {
            echo '<div class="widget-area">';
            dynamic_sidebar($widget_area['id']);
            echo '</div>';
        }
        
        return ob_get_clean();
    }

    public static function registerSidebars(): void {
        // Main Sidebar
        register_sidebar([
            'name'          => __('Main Sidebar', 'autoride'),
            'id'            => 'sidebar-main',
            'description'   => __('Add widgets here to appear in your main sidebar.', 'autoride'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>'
        ]);

        // Footer Widgets
        register_sidebar([
            'name'          => __('Footer Widgets', 'autoride'),
            'id'            => 'sidebar-footer',
            'description'   => __('Add widgets here to appear in your footer.', 'autoride'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>'
        ]);
    }
}
