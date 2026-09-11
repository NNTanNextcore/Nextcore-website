<?php defined('ABSPATH') || exit; get_header(); ?>
<main id="main-content" class="nextcore-inner">
<?php while (have_posts()) : the_post(); ?>
    <?php get_template_part('template-parts/global/page-title'); ?>
    <?php if (nextcore_is_legacy_content()) : ?>
        <div class="nextcore-legacy-wrap"><?php nextcore_render_content(); ?></div>
    <?php elseif (get_post_field('post_name', get_the_ID()) === 'lark') : ?>
        <?php get_template_part('template-parts/service/lark-content'); ?>
    <?php else : ?>
        <div class="nextcore-inner-container nextcore-columns"><?php get_sidebar(); ?><article class="nextcore-reading">
            <?php if (has_post_thumbnail()) { the_post_thumbnail('large', array('class' => 'nextcore-featured')); } ?>
            <?php get_template_part('template-parts/content/gallery'); nextcore_render_content(); ?>
        </article></div>
    <?php endif; ?>
    <?php if (nextcore_contact_url()) : ?><section class="nextcore-service-cta nextcore-inner-container nextcore-native"><h2><?php echo esc_html(nextcore_field('nc_cta_heading', 'Sẵn sàng bắt đầu dự án của bạn?', get_option('page_on_front'))); ?></h2><a class="button" href="<?php echo esc_url(nextcore_contact_url()); ?>"><?php echo esc_html(nextcore_option('nc_header_contact_label', 'Liên hệ ngay')); ?></a></section><?php endif; ?>
    <?php if (comments_open() || get_comments_number()) { comments_template(); } ?>
<?php endwhile; ?>
</main>
<?php get_footer(); ?>
