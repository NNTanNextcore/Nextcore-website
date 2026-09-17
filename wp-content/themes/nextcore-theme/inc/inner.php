<?php
defined('ABSPATH') || exit;

function nextcore_inner_title() {
    if (is_404()) { return __('Không tìm thấy trang', 'nextcore-theme'); }
    if (is_search()) { return sprintf(__('Kết quả tìm kiếm: %s', 'nextcore-theme'), get_search_query()); }
    if (is_home()) { return get_the_title(get_option('page_for_posts')) ?: __('Tin tức', 'nextcore-theme'); }
    if (is_category() || is_tag() || is_tax()) { return single_term_title('', false); }
    if (is_archive()) { return wp_strip_all_tags(get_the_archive_title()); }
    return get_the_title(get_queried_object_id());
}

function nextcore_native_breadcrumbs() {
    $items = array(array(__('Trang chủ', 'nextcore-theme'), nextcore_home_url()));
    $object = get_queried_object();
    if (is_singular('post') || is_category() || is_tag() || is_date() || is_author()) {
        $page = get_option('page_for_posts');
        if ($page) { $items[] = array(get_the_title($page), nextcore_localized_url(get_permalink($page))); }
    } elseif (is_singular('dich-vu') || is_tax('danh-muc-dich-vu')) {
        $items[] = array(__('Dịch vụ', 'nextcore-theme'), nextcore_page_url('dich-vu'));
    }
    if (is_page() && $object) {
        foreach (array_reverse(get_post_ancestors($object)) as $parent) { $items[] = array(get_the_title($parent), nextcore_localized_url(get_permalink($parent))); }
    } elseif (($object instanceof WP_Term) && is_taxonomy_hierarchical($object->taxonomy)) {
        foreach (array_reverse(get_ancestors($object->term_id, $object->taxonomy, 'taxonomy')) as $id) {
            $term = get_term($id, $object->taxonomy);
            if ($term && !is_wp_error($term)) { $items[] = array($term->name, get_term_link($term)); }
        }
    } elseif (is_singular(array('post', 'dich-vu'))) {
        $terms = get_the_terms(get_queried_object_id(), is_singular('dich-vu') ? 'danh-muc-dich-vu' : 'category');
        if ($terms && !is_wp_error($terms)) { $items[] = array($terms[0]->name, get_term_link($terms[0])); }
    }
    $items[] = array(nextcore_inner_title(), '');
    echo '<ol>';
    foreach ($items as $i => $item) {
        echo '<li>';
        if ($item[1] && !is_wp_error($item[1])) { echo '<a href="' . esc_url(nextcore_localized_url($item[1])) . '">' . esc_html($item[0]) . '</a>'; }
        else { echo '<span aria-current="page">' . esc_html($item[0]) . '</span>'; }
        echo '</li>';
    }
    echo '</ol>';
}

function nextcore_sidebar_terms($taxonomy, $parent = 0, $terms = null) {
    if ($terms === null) { $terms = get_terms(array('taxonomy' => $taxonomy, 'hide_empty' => false)); }
    if (is_wp_error($terms) || !$terms) { return; }
    $children = array_filter($terms, function ($term) use ($parent) { return (int) $term->parent === (int) $parent; });
    if (!$children) { return; }
    echo '<ul>';
    foreach ($children as $term) {
        $url = get_term_link($term);
        if (is_wp_error($url)) { continue; }
        $active = (is_tax($taxonomy) || is_category()) && get_queried_object_id() === $term->term_id;
        echo '<li><a ' . ($active ? 'aria-current="page" ' : '') . 'href="' . esc_url(nextcore_localized_url($url)) . '"><span>' . esc_html($term->name) . '</span><span class="nextcore-term-count">' . absint($term->count) . '</span></a>';
        nextcore_sidebar_terms($taxonomy, $term->term_id, $terms);
        echo '</li>';
    }
    echo '</ul>';
}

function nextcore_inner_assets() {
    if (is_front_page()) { return; }
    wp_enqueue_style('nextcore-inner', get_theme_file_uri('/assets/css/inner.css'), array('nextcore-native'), nextcore_asset_version('/assets/css/inner.css'));
    if (is_page(array('ve-chung-toi', 'outsource'))) {
        wp_enqueue_style('nextcore-theme-pages', get_theme_file_uri('/assets/css/theme-pages.css'), array('nextcore-inner'), nextcore_asset_version('/assets/css/theme-pages.css'));
    }
    if (is_page('lien-he')) {
        wp_enqueue_style('nextcore-contact', get_theme_file_uri('/assets/css/contact.css'), array('nextcore-inner'), nextcore_asset_version('/assets/css/contact.css'));
    }
    if (is_singular('dich-vu') && get_post_field('post_name', get_queried_object_id()) === 'lark') {
        wp_enqueue_style('nextcore-lark', get_theme_file_uri('/assets/css/lark.css'), array('nextcore-inner'), nextcore_asset_version('/assets/css/lark.css'));
    }
}
add_action('wp_enqueue_scripts', 'nextcore_inner_assets', 30);

function nextcore_sidebar_widget_compatibility($instance, $widget, $args) {
    // These taxonomies are already rendered natively above the optional widgets.
    if (($args['id'] ?? '') === 'nextcore-sidebar' && in_array($widget->id_base, array('categories', 'wpcategorieswidget'), true)) {
        return false;
    }
    return $instance;
}
add_filter('widget_display_callback', 'nextcore_sidebar_widget_compatibility', 10, 3);
