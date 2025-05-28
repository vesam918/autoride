<?php
use AutoRide\Theme\Core\ThemeSetup;

$id = (int)ThemeSetup::getOption('page_404_page_id', 0);

if ($id <= 0) {
    get_header();
    ?>
    <div class="container mx-auto px-4 py-16 text-center">
        <h1 class="text-4xl font-bold mb-4"><?php esc_html_e('Error 404. Page Not Found.', 'autoride') ?></h1>
        <p class="text-gray-600 mb-8"><?php esc_html_e('The page you are looking for cannot be found.', 'autoride'); ?></p>
        <div class="inline-block">
            <a href="<?php echo esc_url(get_home_url()); ?>" 
               class="bg-primary text-white px-6 py-3 rounded-lg transition-colors duration-200 hover:bg-primary-dark">
                <?php esc_html_e('Back To Home', 'autoride'); ?>
            </a>
        </div>
    </div>
    <?php
    get_footer();
} else {
    $url = get_permalink($id);
    if ($url === false) {
        wp_safe_redirect(get_home_url());
    } else {
        wp_safe_redirect($url);
    }
    exit;
}
