<?php
/** Template Name: Nextcore Services Page */
defined('ABSPATH') || exit;
if (!is_page('dich-vu')) { require __DIR__ . '/page.php'; return; }
get_header();
?>
<main id="main-content" class="nextcore-inner">
<?php get_template_part('template-parts/global/page-title'); ?>
<div class="nextcore-inner-container nextcore-columns">
    <?php get_sidebar(); ?>
    <div class="nextcore-listing-content">
    <?php
    $paged = max(1, get_query_var('paged'), get_query_var('page'));
    $services = new WP_Query(array('post_type' => 'dich-vu', 'post_status' => 'publish', 'posts_per_page' => 8, 'paged' => $paged));
    if ($services->have_posts()) : ?>
        <div class="nextcore-card-grid"><?php while ($services->have_posts()) : $services->the_post(); get_template_part('template-parts/content/card'); endwhile; ?></div>
        <nav class="pagination"><?php echo wp_kses_post(paginate_links(array('total' => $services->max_num_pages, 'current' => $paged))); ?></nav>
    <?php else : get_template_part('template-parts/content/none'); endif; wp_reset_postdata(); ?>
    </div>
</div>
</main>
<?php get_footer(); ?>
