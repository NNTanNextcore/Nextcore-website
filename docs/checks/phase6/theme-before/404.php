<?php defined('ABSPATH') || exit; get_header(); ?>
<main id="main-content" class="nextcore-shell">
    <h1><?php esc_html_e('Không tìm thấy trang', 'nextcore-theme'); ?></h1>
    <a href="<?php echo esc_url(nextcore_home_url()); ?>"><?php esc_html_e('Về trang chủ', 'nextcore-theme'); ?></a>
    <?php get_search_form(); ?>
</main>
<?php get_footer(); ?>

