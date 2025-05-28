<?php
namespace AutoRide\Theme\Core;

use AutoRide\Theme\Admin\AdminSetup;
use AutoRide\Theme\Assets\AssetsManager;
use AutoRide\Theme\Features\MaintenanceMode;
use AutoRide\Theme\Integration\WooCommerce;

class ThemeSetup {
    private static ?ThemeSetup $instance = null;
    private AssetsManager $assets;
    private AdminSetup $admin;
    private static array $options = [];
    
    public function __construct(AssetsManager $assets, AdminSetup $admin) {
        $this->assets = $assets;
        $this->admin = $admin;
        self::$options = get_option('autoride_theme_options', []);
    }

    public static function getInstance(): ThemeSetup {
        if (self::$instance === null) {
            self::$instance = new self(
                new AssetsManager(),
                new AdminSetup()
            );
        }
        return self::$instance;
    }

    public function init(): void {
        $this->setupThemeSupport();
        $this->registerHooks();
        $this->initializeFeatures();
    }

    public static function getOption(string $key, $default = null) {
        return self::$options[$key] ?? $default;
    }

    public static function getOptionPrefix($post): string {
        if (is_singular()) {
            return 'single_' . get_post_type($post) . '_';
        }
        return 'archive_';
    }

    public static function getPostMeta($post): array {
        $meta = get_post_meta(get_the_ID($post));
        return is_array($meta) ? $meta : [];
    }

    public static function getGlobalOption($post, string $key, string $prefix = '', bool $usePrefix = false): string {
        if ($usePrefix) {
            $key = $prefix . $key;
        }
        return self::getOption($key, '');
    }

    private function setupThemeSupport(): void {
        add_theme_support('html5', [
            'style',
            'script',
            'navigation-widgets',
            'gallery',
            'comment-form',
            'comment-list'
        ]);
        
        add_theme_support('wp-block-styles');
        add_theme_support('editor-styles');
        add_theme_support('responsive-embeds');
        add_theme_support('align-wide');
        add_theme_support('custom-spacing');
        
        add_theme_support('title-tag');
        add_theme_support('post-thumbnails');
        add_theme_support('automatic-feed-links');
        add_theme_support('custom-header');
        add_theme_support('custom-background');
        add_theme_support('woocommerce');
    }

    private function registerHooks(): void {
        // Assets
        add_action('wp_enqueue_scripts', [$this->assets, 'enqueueStyles']);
        add_action('wp_enqueue_scripts', [$this->assets, 'enqueueScripts']);
        add_action('admin_enqueue_scripts', [$this->assets, 'enqueueAdminAssets']);
        
        // Navigation
        add_action('after_setup_theme', function() {
            register_nav_menus([
                'main_menu' => __('Main Menu', 'autoride'),
                'footer_menu' => __('Footer Menu', 'autoride')
            ]);
        });

        // Security
        add_filter('upload_mimes', [$this, 'allowedMimeTypes']);
        add_filter('wp_kses_allowed_html', [$this, 'allowedHtmlTags'], 10, 2);
    }

    private function initializeFeatures(): void {
        // Initialize WooCommerce support if active
        if (class_exists('WooCommerce')) {
            (new WooCommerce())->init();
        }

        // Initialize Maintenance Mode
        (new MaintenanceMode())->init();
    }

    public function allowedMimeTypes(array $mimes): array {
        // Add security checks for mime types
        return array_filter($mimes, function($mime) {
            return !preg_match('/^(php|phtml|exe|js)/i', $mime);
        });
    }

    public function allowedHtmlTags(array $tags, string $context): array {
        // Add modern HTML5 tags support with proper security attributes
        if ($context === 'post') {
            $tags['figure'] = [
                'class' => true,
                'id' => true
            ];
            $tags['figcaption'] = [
                'class' => true,
                'id' => true
            ];
        }
        return $tags;
    }
}
