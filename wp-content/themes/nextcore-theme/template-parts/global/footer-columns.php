<?php defined('ABSPATH') || exit; ?>
<div class="nextcore-footer-columns">
    <div class="nextcore-footer-brand"><?php esc_html_e('Công ty Cổ phần Phần mềm Nextcore', 'nextcore-theme'); ?></div>
    <nav aria-label="<?php esc_attr_e('Về Nextcore', 'nextcore-theme'); ?>"><h2><?php esc_html_e('Về Nextcore', 'nextcore-theme'); ?></h2><?php wp_nav_menu(array('theme_location' => 'footer_about', 'container' => false, 'fallback_cb' => false)); ?></nav>
    <nav aria-label="<?php esc_attr_e('Hỗ trợ', 'nextcore-theme'); ?>"><h2><?php esc_html_e('Hỗ trợ', 'nextcore-theme'); ?></h2><?php wp_nav_menu(array('theme_location' => 'footer_support', 'container' => false, 'fallback_cb' => false)); ?></nav>
    <section><h2><?php esc_html_e('Liên hệ', 'nextcore-theme'); ?></h2><?php get_template_part('template-parts/global/contact-details'); ?></section>
    <section class="nextcore-social"><h2><?php esc_html_e('Kết nối', 'nextcore-theme'); ?></h2><?php /* Phase 4: verified G12 social links only. */ ?></section>
</div>

