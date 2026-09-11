<?php defined('ABSPATH') || exit; ?>
<aside class="nextcore-sidebar nextcore-shell">
    <?php foreach (array('danh-muc-dich-vu' => __('Danh mục dịch vụ', 'nextcore-theme'), 'category' => __('Danh mục bài viết', 'nextcore-theme')) as $taxonomy => $label) : ?>
    <?php if (taxonomy_exists($taxonomy)) : ?>
    <nav class="nextcore-sidebar-block" aria-label="<?php echo esc_attr($label); ?>">
        <h2><?php echo esc_html($label); ?></h2>
        <?php nextcore_sidebar_terms($taxonomy); ?>
    </nav>
    <?php endif; endforeach; ?>
    <?php if (is_active_sidebar('nextcore-sidebar')) { dynamic_sidebar('nextcore-sidebar'); } ?>
</aside>
