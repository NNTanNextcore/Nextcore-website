<?php defined('ABSPATH') || exit; get_header(); ?>
<main id="main-content" class="nextcore-inner">
    <?php get_template_part('template-parts/global/page-title'); ?>
    <div class="nextcore-inner-container nextcore-empty">
        <p><?php esc_html_e('Trang bạn tìm không tồn tại hoặc đã được chuyển đi.', 'nextcore-theme'); ?></p>
        <a class="button" href="<?php echo esc_url(nextcore_home_url()); ?>"><?php esc_html_e('Về trang chủ', 'nextcore-theme'); ?></a>
        <?php get_search_form(); ?>
    </div>
</main>
<?php get_footer(); ?>
