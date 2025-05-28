<?php
namespace AutoRide\Theme\Integration;

class WooCommerce {
    public function init(): void {
        // Add theme support for WooCommerce features
        add_theme_support('woocommerce');
        add_theme_support('wc-product-gallery-zoom');
        add_theme_support('wc-product-gallery-lightbox');
        add_theme_support('wc-product-gallery-slider');

        // Remove outdated WooCommerce styles
        add_filter('woocommerce_enqueue_styles', '__return_empty_array');

        // Add custom WooCommerce styles and scripts
        add_action('wp_enqueue_scripts', [$this, 'enqueueWooCommerceAssets']);

        // Override WooCommerce templates if needed
        add_filter('woocommerce_locate_template', [$this, 'locateTemplate'], 10, 3);
    }

    public function enqueueWooCommerceAssets(): void {
        wp_enqueue_style('autoride-woocommerce', get_template_directory_uri() . '/assets/css/woocommerce.css', [], '2.0.0');
        wp_enqueue_script('autoride-woocommerce', get_template_directory_uri() . '/assets/js/woocommerce.js', ['jquery'], '2.0.0', true);
    }

    public function locateTemplate($template, $template_name, $template_path): string {
        $theme_path = get_template_directory() . '/woocommerce/' . $template_name;
        if (file_exists($theme_path)) {
            return $theme_path;
        }
        return $template;
    }
}
