<?php defined('ABSPATH') || exit; ?>
<header>
<?php if (is_search()) : ?>
    <h1><?php printf(esc_html__('Kết quả tìm kiếm: %s', 'nextcore-theme'), esc_html(get_search_query(false))); ?></h1>
<?php elseif (is_home()) : ?>
    <h1><?php esc_html_e('Tin tức', 'nextcore-theme'); ?></h1>
<?php elseif (is_archive()) : the_archive_title('<h1>', '</h1>'); the_archive_description('<div>', '</div>'); ?>
<?php else : ?>
    <h1><?php esc_html_e('Bài viết', 'nextcore-theme'); ?></h1>
<?php endif; ?>
</header>

