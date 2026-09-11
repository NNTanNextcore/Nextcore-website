<?php defined('ABSPATH') || exit; ?>
<article id="post-<?php the_ID(); ?>" <?php post_class('nextcore-entry'); ?>>
    <?php get_template_part('template-parts/global/page-title'); ?>
    <div class="nextcore-inner-container nextcore-columns">
        <?php get_sidebar(); ?>
        <div class="nextcore-reading">
            <p class="nextcore-post-meta"><time datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(get_the_date()); ?></time></p>
            <?php if (has_post_thumbnail()) { the_post_thumbnail('large', array('class' => 'nextcore-featured')); } ?>
            <?php get_template_part('template-parts/content/gallery'); ?>
            <?php nextcore_render_content(); ?>
            <?php if (comments_open() || get_comments_number()) { comments_template(); } ?>
        </div>
    </div>
</article>
