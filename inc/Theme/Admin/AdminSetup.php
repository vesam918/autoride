<?php
namespace AutoRide\Theme\Admin;

use AutoRide\Theme\Core\ThemeSetup;

class AdminSetup {
    private const THEME_OPTIONS_PAGE = 'autoride-theme-options';
    private array $themeOptions;

    public function __construct() {
        $this->themeOptions = $this->getDefaultOptions();
    }

    public function init(): void {
        // Add admin menu
        add_action('admin_menu', [$this, 'addAdminMenu']);
        
        // Register settings
        add_action('admin_init', [$this, 'registerSettings']);
        
        // Add meta boxes
        add_action('add_meta_boxes', [$this, 'addMetaBoxes']);
        
        // Save post meta
        add_action('save_post', [$this, 'savePostMeta']);
        
        // Add admin notices
        add_action('admin_notices', [$this, 'adminNotices']);
        
        // Add plugin dependencies
        add_action('tgmpa_register', [$this, 'registerRequiredPlugins']);
    }

    public function addAdminMenu(): void {
        add_theme_page(
            __('Theme Options', 'autoride'),
            __('Theme Options', 'autoride'),
            'manage_options',
            self::THEME_OPTIONS_PAGE,
            [$this, 'renderOptionsPage']
        );
    }

    public function registerSettings(): void {
        register_setting(
            'autoride_theme_options',
            'autoride_theme_options',
            [$this, 'sanitizeOptions']
        );

        // Add settings sections
        add_settings_section(
            'general_settings',
            __('General Settings', 'autoride'),
            [$this, 'renderGeneralSection'],
            self::THEME_OPTIONS_PAGE
        );

        // Add settings fields
        add_settings_field(
            'primary_color',
            __('Primary Color', 'autoride'),
            [$this, 'renderColorField'],
            self::THEME_OPTIONS_PAGE,
            'general_settings',
            ['id' => 'primary_color']
        );
    }

    public function addMetaBoxes(): void {
        add_meta_box(
            'autoride_page_settings',
            __('Page Settings', 'autoride'),
            [$this, 'renderPageSettings'],
            ['page', 'post'],
            'side',
            'default'
        );
    }

    public function savePostMeta($postId): void {
        // Verify nonce
        if (!isset($_POST['autoride_meta_nonce']) || 
            !wp_verify_nonce($_POST['autoride_meta_nonce'], 'autoride_save_meta')) {
            return;
        }

        // Check autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // Check permissions
        if (!current_user_can('edit_post', $postId)) {
            return;
        }

        // Save meta data
        $fields = [
            'page_layout',
            'sidebar_position',
            'header_style',
            'footer_style'
        ];

        foreach ($fields as $field) {
            if (isset($_POST['autoride_' . $field])) {
                update_post_meta(
                    $postId,
                    '_autoride_' . $field,
                    sanitize_text_field($_POST['autoride_' . $field])
                );
            }
        }
    }

    public function registerRequiredPlugins(): void {
        $plugins = [
            [
                'name' => 'WPBakery Page Builder',
                'slug' => 'js_composer',
                'source' => get_template_directory() . '/plugins/js_composer.zip',
                'required' => true,
                'version' => '6.7.0',
                'force_activation' => false
            ],
            [
                'name' => 'Revolution Slider',
                'slug' => 'revslider',
                'source' => get_template_directory() . '/plugins/revslider.zip',
                'required' => false,
                'version' => '6.5.0',
                'force_activation' => false
            ],
            [
                'name' => 'WooCommerce',
                'slug' => 'woocommerce',
                'required' => true
            ]
        ];

        $config = [
            'id' => 'autoride',
            'default_path' => '',
            'menu' => 'tgmpa-install-plugins',
            'parent_slug' => 'themes.php',
            'capability' => 'manage_options',
            'has_notices' => true,
            'dismissable' => true,
            'is_automatic' => true
        ];

        tgmpa($plugins, $config);
    }

    private function getDefaultOptions(): array {
        return [
            'primary_color' => '#FF700A',
            'secondary_color' => '#556677',
            'text_color' => '#2C3E50',
            'font_family' => 'Lato',
            'header_style' => 'default',
            'footer_style' => 'default',
            'maintenance_mode' => false
        ];
    }

    public function renderOptionsPage(): void {
        if (!current_user_can('manage_options')) {
            return;
        }

        // Check if settings were saved
        if (isset($_GET['settings-updated'])) {
            add_settings_error(
                'autoride_messages',
                'autoride_message',
                __('Settings Saved', 'autoride'),
                'updated'
            );
        }

        // Settings form
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            <?php settings_errors('autoride_messages'); ?>
            <form action="options.php" method="post">
                <?php
                settings_fields('autoride_theme_options');
                do_settings_sections(self::THEME_OPTIONS_PAGE);
                submit_button('Save Settings');
                ?>
            </form>
        </div>
        <?php
    }

    public function sanitizeOptions($input): array {
        $sanitized = [];
        foreach ($input as $key => $value) {
            switch ($key) {
                case 'primary_color':
                case 'secondary_color':
                case 'text_color':
                    $sanitized[$key] = sanitize_hex_color($value);
                    break;
                case 'maintenance_mode':
                    $sanitized[$key] = (bool) $value;
                    break;
                default:
                    $sanitized[$key] = sanitize_text_field($value);
            }
        }
        return $sanitized;
    }

    public function adminNotices(): void {
        global $pagenow;

        // Show notice if required plugins are not installed
        if ($pagenow === 'themes.php' && isset($_GET['activated'])) {
            $required_plugins = [];
            if (!is_plugin_active('js_composer/js_composer.php')) {
                $required_plugins[] = 'WPBakery Page Builder';
            }
            if (!is_plugin_active('woocommerce/woocommerce.php')) {
                $required_plugins[] = 'WooCommerce';
            }

            if (!empty($required_plugins)) {
                $message = sprintf(
                    __('AutoRide theme requires the following plugins: %s. Please install and activate them.', 'autoride'),
                    implode(', ', $required_plugins)
                );
                echo '<div class="notice notice-warning is-dismissible"><p>' . esc_html($message) . '</p></div>';
            }
        }
    }
}
