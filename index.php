<?php
/**
 * The main template file
 *
 * @package AutoRide
 */

get_header(); ?>

<main id="main" class="site-main">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <?php if (have_posts()) : ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php while (have_posts()) : the_post(); ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class('card bg-white rounded-lg shadow-md overflow-hidden transition-transform duration-300 hover:transform hover:scale-105'); ?>>
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="aspect-w-16 aspect-h-9 overflow-hidden">
                                <?php the_post_thumbnail('large', ['class' => 'w-full h-full object-cover']); ?>
                            </div>
                        <?php endif; ?>

                        <div class="p-6">
                            <header class="entry-header mb-4">
                                <?php if (is_sticky() && is_home() && !is_paged()) : ?>
                                    <span class="inline-block bg-primary text-white text-xs px-2 py-1 rounded-full mb-2">
                                        Featured
                                    </span>
                                <?php endif; ?>

                                <?php the_title('<h2 class="entry-title text-2xl font-bold mb-2"><a href="' . esc_url(get_permalink()) . '" class="hover:text-primary transition-colors duration-200">', '</a></h2>'); ?>

                                <div class="entry-meta text-sm text-gray-600">
                                    <time class="entry-date published" datetime="<?php echo esc_attr(get_the_date(DATE_W3C)); ?>">
                                        <?php echo esc_html(get_the_date()); ?>
                                    </time>
                                    <span class="mx-2">•</span>
                                    <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>" class="hover:text-primary">
                                        <?php echo esc_html(get_the_author()); ?>
                                    </a>
                                </div>
                            </header>

                            <div class="entry-content prose prose-sm max-w-none mb-4">
                                <?php the_excerpt(); ?>
                            </div>

                            <footer class="entry-footer">
                                <div class="flex items-center justify-between">
                                    <div class="text-sm text-gray-600">
                                        <?php if ($categories_list = get_the_category_list(', ')) : ?>
                                            <span class="cat-links">
                                                Posted in: <?php echo $categories_list; ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    <a href="<?php echo esc_url(get_permalink()); ?>" class="inline-flex items-center text-primary hover:text-primary-dark transition-colors duration-200">
                                        Read More
                                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </a>
                                </div>
                            </footer>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>

            <?php if ($wp_query->max_num_pages > 1) : ?>
                <nav class="my-8" aria-label="Posts navigation">
                    <div class="flex items-center justify-center space-x-4 text-gray-600">
                        <?php 
                        if ($prev = get_previous_posts_link('<span class="hover:text-primary transition-colors duration-200">← Previous</span>')) {
                            echo $prev;
                            echo '<span class="text-gray-400">·</span>';
                        }
                        echo get_next_posts_link('<span class="hover:text-primary transition-colors duration-200">Next →</span>');
                        ?>
                    </div>
                </nav>
            <?php endif; ?>

        <?php else : ?>
            <div class="no-results py-12 text-center">
                <h2 class="text-2xl font-bold mb-4">Nothing Found</h2>
                <p class="text-gray-600 mb-6">It seems we can't find what you're looking for.</p>
                <?php get_search_form(); ?>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php
get_sidebar();
get_footer();
