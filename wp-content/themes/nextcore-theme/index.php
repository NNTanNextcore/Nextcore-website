<?php
defined('ABSPATH') || exit;
get_header();
?>
<main id="main-content" class="nextcore-shell nextcore-listing">
    <?php get_template_part('template-parts/content/archive-heading'); ?>
    <?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); get_template_part('template-parts/content/card'); endwhile; ?>
        <?php the_posts_pagination(); ?>
    <?php else : get_template_part('template-parts/content/none'); endif; ?>
</main>
<?php get_footer(); ?>

