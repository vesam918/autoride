<?php
namespace AutoRide\Theme\Core;

class Header {
    private ThemeSetup $theme;
    private Helper $helper;

    public function __construct() {
        $this->theme = ThemeSetup::getInstance();
        $this->helper = new Helper();
    }

    public function render($post): string {
        ob_start();
        
        $header_class = ['site-header'];
        $header_style = [];
        
        // Get header options
        $header_type = ThemeSetup::getGlobalOption($post, 'header_type', '', true);
        $header_bg = ThemeSetup::getGlobalOption($post, 'header_background', '', true);
        $is_transparent = ThemeSetup::getGlobalOption($post, 'header_transparent', '', true);
        
        // Add classes based on options
        if ($header_type) {
            $header_class[] = 'header-' . $header_type;
        }
        
        if ($is_transparent === 'true') {
            $header_class[] = 'header-transparent';
        }
        
        if ($header_bg) {
            $header_style['background-color'] = $header_bg;
        }
        
        // Start header output
        ?>
        <header <?php echo $this->helper->createClassAttribute($header_class) . $this->helper->createStyleAttribute($header_style); ?>>
            <div class="container mx-auto px-4">
                <div class="flex items-center justify-between py-4">
                    <!-- Logo -->
                    <div class="flex-shrink-0">
                        <?php if (has_custom_logo()): ?>
                            <?php the_custom_logo(); ?>
                        <?php else: ?>
                            <a href="<?php echo esc_url(home_url('/')); ?>" class="text-2xl font-bold">
                                <?php bloginfo('name'); ?>
                            </a>
                        <?php endif; ?>
                    </div>

                    <!-- Navigation -->
                    <nav class="hidden md:flex space-x-8">
                        <?php
                        wp_nav_menu([
                            'theme_location' => 'main_menu',
                            'container' => false,
                            'menu_class' => 'flex space-x-8',
                            'fallback_cb' => false,
                            'items_wrap' => '<ul class="%2$s">%3$s</ul>',
                            'depth' => 2,
                        ]);
                        ?>
                    </nav>

                    <!-- Mobile Menu Button -->
                    <div class="md:hidden">
                        <button type="button" class="mobile-menu-button text-gray-500 hover:text-gray-600 focus:outline-none">
                            <span class="sr-only"><?php esc_html_e('Open menu', 'autoride'); ?></span>
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div class="mobile-menu hidden md:hidden">
                <div class="container mx-auto px-4 py-3">
                    <?php
                    wp_nav_menu([
                        'theme_location' => 'main_menu',
                        'container' => false,
                        'menu_class' => 'space-y-2',
                        'fallback_cb' => false,
                        'depth' => 1,
                    ]);
                    ?>
                </div>
            </div>
        </header>
        <?php
        return ob_get_clean();
    }
}
