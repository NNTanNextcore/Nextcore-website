<?php defined('ABSPATH') || exit; ?>
<header class="nextcore-page-hero nextcore-shell">
    <div class="nextcore-inner-container">
        <?php get_template_part('template-parts/global/breadcrumbs'); ?>
        <h1><?php echo esc_html(nextcore_inner_title()); ?></h1>
        <?php if (is_tax() || is_category() || is_tag()) : $description = term_description(); if ($description) : ?>
        <div class="nextcore-term-description"><?php echo wp_kses_post($description); ?></div>
        <?php endif; endif; ?>
    </div>
</header>
