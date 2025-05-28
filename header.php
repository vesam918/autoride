<?php
use AutoRide\Theme\Core\ThemeSetup;
use AutoRide\Theme\Core\Post;
use AutoRide\Theme\Core\Header;
use AutoRide\Theme\Core\Validation;
use AutoRide\Theme\Core\Helper;
use AutoRide\Theme\Core\WidgetArea;

global $post, $autoride_parent_post, $autoride_sidebar;

// Initialize theme components
$theme = ThemeSetup::getInstance();
$post_handler = new Post();
$header_handler = new Header();
$validation = new Validation();

// Get parent post
if (($autoride_parent_post = $post_handler->getPost()) === false) {
    $autoride_parent_post = new \stdClass();
    $autoride_parent_post->post = $post;
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="format-detection" content="telephone=no">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>
    
    <div class="min-h-screen bg-white">
        <?php 
        // Header
        echo $header_handler->render($autoride_parent_post->post);
        
        // Page content setup
        $style = [];
        $classes = ['theme-page-content'];
        
        $prefix = ThemeSetup::getOptionPrefix($autoride_parent_post->post);
        $meta = ThemeSetup::getPostMeta($autoride_parent_post->post);
        
        // Get theme options
        $css_class = ThemeSetup::getGlobalOption($autoride_parent_post->post, 'class', $prefix, true);
        $bg_color = ThemeSetup::getGlobalOption($autoride_parent_post->post, 'background_color', $prefix, true);
        $margin_top = ThemeSetup::getGlobalOption($autoride_parent_post->post, 'margin_top', $prefix, true);
        $margin_bottom = ThemeSetup::getGlobalOption($autoride_parent_post->post, 'margin_bottom', $prefix, true);
        
        // Apply classes and styles
        if ($validation->isNotEmpty($css_class)) {
            $classes[] = $css_class;
        }
        if ($validation->isColor($bg_color)) {
            $style['background-color'] = '#' . $bg_color;
        }
        if ($validation->isNumber($margin_top, 0, 99999)) {
            $style['padding-top'] = (int)$margin_top . 'px';
        }
        if ($validation->isNumber($margin_bottom, 0, 99999)) {
            $style['padding-bottom'] = (int)$margin_bottom . 'px';
        }
        
        // Widget area setup
        $widget_area = new WidgetArea();
        $widget_area_data = $widget_area->getWidgetAreaByPost($autoride_parent_post->post, 'widget_area_sidebar', true);
        $sidebar_class = $widget_area->getWidgetAreaCSSClass($widget_area_data);
        
        $sidebar_content = $widget_area->render($widget_area_data);
        
        if ($validation->isEmpty($sidebar_content)) {
            $sidebar_class = 'theme-page-sidebar-disable';
            $widget_area_data = ['id' => 0, 'location' => 0];
        }
        
        $autoride_sidebar = false;
        if (in_array($widget_area_data['location'], [1, 2])) {
            $autoride_sidebar = true;
        }
        
        // Add Gutenberg support
        if (function_exists('has_blocks') && has_blocks()) {
            $sidebar_class .= ' theme-gutenberg-block';
        }
        ?>
        
        <div <?php echo Helper::createClassAttribute($classes) . Helper::createStyleAttribute($style); ?>>
            <div class="theme-main theme-clear-fix <?php echo esc_attr($sidebar_class); ?>">
                <?php if ($widget_area_data['location'] == 1): ?>
                    <div class="theme-column-left">
                        <?php $widget_area->render($widget_area_data); ?>
                    </div>
                    <div class="theme-column-right">
                <?php elseif ($widget_area_data['location'] == 2): ?>
                    <div class="theme-column-left">
                <?php endif; ?>
