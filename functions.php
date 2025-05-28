<?php
/**
 * AutoRide Theme Functions
 * 
 * @package AutoRide
 * @version 2.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

// Define theme constants
define('AUTORIDE_VERSION', '2.0.0');
define('AUTORIDE_PATH', get_template_directory());
define('AUTORIDE_URL', get_template_directory_uri());
define('AUTORIDE_ASSETS_URL', AUTORIDE_URL . '/assets');
define('AUTORIDE_INC_PATH', AUTORIDE_PATH . '/inc');

// Autoloader
spl_autoload_register(function ($class) {
    // Project-specific namespace prefix
    $prefix = 'AutoRide\\Theme\\';
    $base_dir = AUTORIDE_INC_PATH . '/Theme/';

    // Check if the class uses the namespace prefix
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    // Get the relative class name
    $relative_class = substr($class, $len);

    // Replace namespace separators with directory separators
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    // If the file exists, require it
    if (file_exists($file)) {
        require $file;
    }
});

// Initialize theme
function autoride_init(): void {
    // Initialize core components
    $assets_manager = new AutoRide\Theme\Assets\AssetsManager();
    $admin_setup = new AutoRide\Theme\Admin\AdminSetup();
    
    // Initialize theme setup with dependencies
    $theme_setup = new AutoRide\Theme\Core\ThemeSetup($assets_manager, $admin_setup);
    $theme_setup->init();
    
    // Initialize admin if in admin area
    if (is_admin()) {
        $admin_setup->init();
    }

    // Register widget areas
    AutoRide\Theme\Core\WidgetArea::registerSidebars();
}

// Hook initialization
add_action('after_setup_theme', 'autoride_init');

// Theme setup
function autoride_setup(): void {
    // Load theme textdomain
    load_theme_textdomain('autoride', AUTORIDE_PATH . '/languages');

    // Add default posts and comments RSS feed links to head
    add_theme_support('automatic-feed-links');

    // Enable support for Post Thumbnails
    add_theme_support('post-thumbnails');

    // Add support for responsive embeds
    add_theme_support('responsive-embeds');

    // Add support for custom logo
    add_theme_support('custom-logo', [
        'height'      => 100,
        'width'       => 400,
        'flex-height' => true,
        'flex-width'  => true,
    ]);

    // Add support for editor styles
    add_theme_support('editor-styles');

    // Add support for HTML5
    add_theme_support('html5', [
        'navigation-widgets',
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'script',
        'style',
    ]);

    // Register nav menus
    register_nav_menus([
        'primary' => __('Primary Menu', 'autoride'),
        'footer'  => __('Footer Menu', 'autoride'),
    ]);
}
add_action('after_setup_theme', 'autoride_setup');

// Security enhancements
function autoride_security_headers(): void {
    if (!is_admin()) {
        // Remove WordPress version
        remove_action('wp_head', 'wp_generator');
        
        // Add security headers
        header('X-Content-Type-Options: nosniff');
        header('X-Frame-Options: SAMEORIGIN');
        header('X-XSS-Protection: 1; mode=block');
        header('Referrer-Policy: strict-origin-when-cross-origin');
        
        // Add Content Security Policy
        $csp = "default-src 'self'; " .
               "script-src 'self' 'unsafe-inline' 'unsafe-eval' *.googleapis.com *.gstatic.com; " .
               "style-src 'self' 'unsafe-inline' *.googleapis.com; " .
               "img-src 'self' data: *.googleapis.com *.gstatic.com; " .
               "font-src 'self' data: *.gstatic.com; " .
               "connect-src 'self' *.googleapis.com; " .
               "frame-src 'self';";
        
        header("Content-Security-Policy: " . $csp);
    }
}
add_action('send_headers', 'autoride_security_headers');

// Disable XML-RPC
add_filter('xmlrpc_enabled', '__return_false');

// Remove WordPress REST API links from header
remove_action('wp_head', 'rest_output_link_wp_head');
remove_action('template_redirect', 'rest_output_link_header', 11);

// Disable pingbacks
function autoride_disable_pingbacks(&$links) {
    foreach ($links as $l => $link)
        if (0 === strpos($link, get_option('home')))
            unset($links[$l]);
}
add_action('pre_ping', 'autoride_disable_pingbacks');
