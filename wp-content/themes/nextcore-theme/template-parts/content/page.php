<?php defined('ABSPATH') || exit; ?>
<article id="post-<?php the_ID(); ?>" <?php post_class('nextcore-entry'); ?>>
    <?php get_template_part('template-parts/global/page-title'); ?>
    <?php if (nextcore_is_legacy_content()) : ?>
        <div class="nextcore-legacy-wrap"><?php nextcore_render_content(); ?></div>
    <?php else : ?>
        <div class="nextcore-inner-container nextcore-reading"><?php nextcore_render_content(); ?></div>
    <?php endif; ?>
</article>
