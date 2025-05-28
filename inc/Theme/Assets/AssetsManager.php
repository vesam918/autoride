<?php
namespace AutoRide\Theme\Assets;

class AssetsManager {
    private const THEME_VERSION = '2.0.0';
    private array $scripts = [];
    private array $styles = [];

    public function __construct() {
        $this->initAssets();
    }

    private function initAssets(): void {
        // Define core styles
        $this->styles = [
            'autoride-main' => [
                'src' => get_stylesheet_uri(),
                'deps' => [],
                'media' => 'all'
            ],
            'autoride-google-fonts' => [
                'src' => 'https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700;900&display=swap',
                'deps' => [],
                'media' => 'all'
            ]
        ];

        // Define core scripts
        $this->scripts = [
            'autoride-main' => [
                'src' => get_template_directory_uri() . '/assets/js/main.js',
                'deps' => ['wp-element'],
                'in_footer' => true
            ],
            'autoride-booking' => [
                'src' => get_template_directory_uri() . '/assets/js/booking.js',
                'deps' => ['wp-api-fetch'],
                'in_footer' => true
            ]
        ];
    }

    public function enqueueStyles(): void {
        // Enqueue theme styles
        foreach ($this->styles as $handle => $style) {
            wp_enqueue_style(
                $handle,
                $style['src'],
                $style['deps'],
                self::THEME_VERSION,
                $style['media']
            );
        }

        // Add custom CSS variables
        wp_add_inline_style('autoride-main', $this->generateCustomProperties());
    }

    public function enqueueScripts(): void {
        // Enqueue theme scripts
        foreach ($this->scripts as $handle => $script) {
            wp_enqueue_script(
                $handle,
                $script['src'],
                $script['deps'],
                self::THEME_VERSION,
                $script['in_footer']
            );
        }

        // Localize script data
        wp_localize_script('autoride-main', 'autorideData', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('autoride_nonce'),
            'isLoggedIn' => is_user_logged_in(),
            'themeUrl' => get_template_directory_uri(),
            'siteUrl' => get_site_url()
        ]);
    }

    public function enqueueAdminAssets(): void {
        // Admin styles
        wp_enqueue_style(
            'autoride-admin',
            get_template_directory_uri() . '/assets/css/admin.css',
            [],
            self::THEME_VERSION
        );

        // Admin scripts
        wp_enqueue_script(
            'autoride-admin',
            get_template_directory_uri() . '/assets/js/admin.js',
            ['jquery', 'wp-api-fetch'],
            self::THEME_VERSION,
            true
        );
    }

    private function generateCustomProperties(): string {
        // Get theme options
        $primaryColor = get_theme_mod('primary_color', '#FF700A');
        $secondaryColor = get_theme_mod('secondary_color', '#556677');
        $textColor = get_theme_mod('text_color', '#2C3E50');

        return "
            :root {
                --autoride-color-primary: {$primaryColor};
                --autoride-color-secondary: {$secondaryColor};
                --autoride-color-text: {$textColor};
                --autoride-font-primary: 'Lato', sans-serif;
                --autoride-spacing-unit: 8px;
                --autoride-border-radius: 4px;
                --autoride-transition: all 0.3s ease;
            }
        ";
    }

    public function deferScripts(string $tag, string $handle): string {
        // Add defer attribute to non-critical scripts
        if (isset($this->scripts[$handle]) && !in_array($handle, ['jquery'])) {
            return str_replace(' src', ' defer src', $tag);
        }
        return $tag;
    }

    public function preloadFonts(): void {
        // Preload critical fonts
        add_action('wp_head', function() {
            echo '<link rel="preload" href="' . get_template_directory_uri() . '/assets/fonts/lato-regular.woff2" as="font" type="font/woff2" crossorigin>';
        }, 1);
    }
}
