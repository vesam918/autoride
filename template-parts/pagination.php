<?php
/**
 * Template part for displaying pagination
 */

global $wp_query;
if ($wp_query->max_num_pages <= 1) {
    return;
}
?>

<nav class="my-8">
    <div class="flex justify-center gap-4">
        <?php if (get_previous_posts_link()) : ?>
            <div><?php previous_posts_link('Previous'); ?></div>
        <?php endif; ?>
        <?php if (get_next_posts_link()) : ?>
            <div><?php next_posts_link('Next'); ?></div>
        <?php endif; ?>
    </div>
</nav>
