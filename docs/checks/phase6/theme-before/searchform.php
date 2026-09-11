<?php defined('ABSPATH') || exit; ?>
<form role="search" method="get" class="nextcore-search-form" action="<?php echo esc_url(nextcore_home_url()); ?>">
    <label><span><?php esc_html_e('Tìm kiếm', 'nextcore-theme'); ?></span>
        <input type="search" name="s" value="<?php echo esc_attr(get_search_query(false)); ?>">
    </label>
    <button type="submit"><?php esc_html_e('Tìm', 'nextcore-theme'); ?></button>
</form>

