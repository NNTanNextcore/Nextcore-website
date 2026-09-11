<?php defined('ABSPATH') || exit; ?>
<?php get_template_part('template-parts/global/page-title'); ?>
<div class="nextcore-inner-container nextcore-columns">
    <?php get_sidebar(); ?>
    <div class="nextcore-listing-content">
    <?php if (have_posts()) : ?>
        <div class="nextcore-card-grid"><?php while (have_posts()) : the_post(); get_template_part('template-parts/content/card'); endwhile; ?></div>
        <?php the_posts_pagination(array('mid_size' => 1, 'prev_text' => __('Trước', 'nextcore-theme'), 'next_text' => __('Tiếp', 'nextcore-theme'))); ?>
    <?php else : get_template_part('template-parts/content/none'); endif; ?>
    </div>
</div>
