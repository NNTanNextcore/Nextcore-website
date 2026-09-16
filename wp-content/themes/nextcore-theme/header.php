<?php defined('ABSPATH') || exit; ?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="theme-color" content="#ffffff">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="nextcore-skip-link" href="#main-content"><?php esc_html_e('Chuyển đến nội dung', 'nextcore-theme'); ?></a>
<?php get_template_part('template-parts/global/icons'); ?>
<header class="site-header nextcore-site-header nextcore-native" id="site-header">
<div class="container header-inner">
    <a class="brand" href="<?php echo esc_url(nextcore_home_url()); ?>" aria-label="<?php esc_attr_e('Nextcore — Trang chủ', 'nextcore-theme'); ?>">
    <?php $logo = get_theme_mod('custom_logo'); if ($logo) { echo wp_get_attachment_image($logo, 'full', false, array('alt' => get_bloginfo('name'))); } else { ?>
        <img src="<?php echo esc_url(nextcore_asset('images/nextcore-logo.png')); ?>" width="200" height="90" alt="Nextcore">
    <?php } ?></a>
    <nav class="main-nav nextcore-desktop-nav" aria-label="<?php esc_attr_e('Điều hướng chính', 'nextcore-theme'); ?>"><?php nextcore_navigation(); ?></nav>
    <div class="header-actions">
        <button class="icon-button" data-nextcore-search aria-label="<?php esc_attr_e('Tìm kiếm', 'nextcore-theme'); ?>"><svg class="icon"><use href="#i-search"/></svg></button>
        <div class="nextcore-desktop-actions"><?php get_template_part('template-parts/global/theme-switch'); ?></div>
        <?php if (nextcore_contact_url()) : ?><a class="button button-small nextcore-header-cta" href="<?php echo esc_url(nextcore_contact_url()); ?>"><?php echo esc_html(nextcore_option('nc_header_contact_label', 'Liên hệ ngay')); ?> <span aria-hidden="true">→</span></a><?php endif; ?>
    </div>
    <button class="menu-toggle nextcore-mobile-toggle" type="button" aria-expanded="false" aria-controls="nextcore-mobile-panel" aria-label="<?php esc_attr_e('Menu', 'nextcore-theme'); ?>" hidden><span></span><span></span><span></span></button>
    <div id="nextcore-mobile-panel" class="nextcore-mobile-panel">
        <nav aria-label="<?php esc_attr_e('Điều hướng di động', 'nextcore-theme'); ?>"><?php nextcore_navigation(true); ?></nav>
        <div class="nextcore-mobile-actions"><?php get_template_part('template-parts/global/theme-switch'); ?></div>
    </div>
</div>
</header>
<dialog class="nextcore-search-dialog nextcore-native" aria-labelledby="nextcore-search-title">
    <button class="dialog-close" type="button" aria-label="<?php esc_attr_e('Đóng tìm kiếm', 'nextcore-theme'); ?>">×</button>
    <h2 id="nextcore-search-title"><?php esc_html_e('Bạn đang tìm gì?', 'nextcore-theme'); ?></h2>
    <?php get_search_form(); ?>
</dialog>
