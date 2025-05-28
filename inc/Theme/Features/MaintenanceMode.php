<?php
namespace AutoRide\Theme\Features;

class MaintenanceMode {
    private bool $isEnabled;
    private int $pageId;
    private array $allowedUsers;

    public function __construct() {
        $this->isEnabled = (bool) get_option('autoride_maintenance_mode_enable', false);
        $this->pageId = (int) get_option('autoride_maintenance_mode_page_id', 0);
        $this->allowedUsers = $this->getAllowedUsers();
    }

    public function init(): void {
        if ($this->isEnabled && !is_admin()) {
            add_action('template_redirect', [$this, 'checkMaintenanceMode']);
            add_action('admin_bar_menu', [$this, 'addAdminBarNotice'], 100);
            add_action('wp_head', [$this, 'addMetaRobots']);
        }
    }

    public function checkMaintenanceMode(): void {
        // Allow access for allowed users
        if ($this->isUserAllowed()) {
            return;
        }

        // If no maintenance page is set, show default message
        if (!$this->pageId) {
            wp_die(
                $this->getDefaultMessage(),
                esc_html__('Maintenance Mode', 'autoride'),
                [
                    'response' => 503,
                    'back_link' => false
                ]
            );
        }

        // Show maintenance page
        if (!is_page($this->pageId)) {
            wp_redirect(get_permalink($this->pageId));
            exit;
        }
    }

    private function isUserAllowed(): bool {
        // Check if user is logged in and has permission
        if (!is_user_logged_in()) {
            return false;
        }

        $currentUser = wp_get_current_user();
        return in_array($currentUser->ID, $this->allowedUsers) || current_user_can('manage_options');
    }

    private function getAllowedUsers(): array {
        $allowedUsers = get_option('autoride_maintenance_mode_allowed_users', []);
        return array_filter((array) $allowedUsers, 'is_numeric');
    }

    public function addAdminBarNotice($adminBar): void {
        if (!current_user_can('manage_options')) {
            return;
        }

        $adminBar->add_menu([
            'id' => 'maintenance-mode',
            'title' => sprintf(
                '<span class="ab-icon"></span><span class="ab-label">%s</span>',
                esc_html__('Maintenance Mode Active', 'autoride')
            ),
            'href' => admin_url('themes.php?page=theme-options#maintenance'),
            'meta' => [
                'class' => 'maintenance-mode-notice',
                'title' => esc_html__('Maintenance Mode is currently active', 'autoride')
            ]
        ]);
    }

    public function addMetaRobots(): void {
        // Prevent search engines from indexing maintenance page
        echo '<meta name="robots" content="noindex,nofollow" />' . "\n";
    }

    private function getDefaultMessage(): string {
        $message = '<div class="maintenance-mode-message">';
        $message .= '<h1>' . esc_html__('Website Under Maintenance', 'autoride') . '</h1>';
        $message .= '<p>' . esc_html__('We are currently performing scheduled maintenance. We will be back online shortly.', 'autoride') . '</p>';
        $message .= '</div>';
        
        // Add basic styling
        $message .= '<style>
            .maintenance-mode-message {
                text-align: center;
                padding: 50px 20px;
                font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
            }
            .maintenance-mode-message h1 {
                color: #1a1a1a;
                font-size: 2.5em;
                margin-bottom: 20px;
            }
            .maintenance-mode-message p {
                color: #666;
                font-size: 1.2em;
                line-height: 1.6;
            }
        </style>';

        return $message;
    }

    public static function activate(): void {
        // Set default options when theme is activated
        if (!get_option('autoride_maintenance_mode_enable')) {
            add_option('autoride_maintenance_mode_enable', false);
        }
        if (!get_option('autoride_maintenance_mode_page_id')) {
            add_option('autoride_maintenance_mode_page_id', 0);
        }
        if (!get_option('autoride_maintenance_mode_allowed_users')) {
            add_option('autoride_maintenance_mode_allowed_users', []);
        }
    }
}
