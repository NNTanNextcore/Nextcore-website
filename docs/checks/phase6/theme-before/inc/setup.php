<?php
defined('ABSPATH') || exit;

function nextcore_setup() {
    load_theme_textdomain('nextcore-theme', get_theme_file_path('/languages'));
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo');
    add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script'));
    add_theme_support('responsive-embeds');
    register_nav_menus(array(
        'home_primary' => __('Homepage desktop', 'nextcore-theme'),
        'home_mobile' => __('Homepage mobile', 'nextcore-theme'),
        'primary' => __('Inner pages desktop', 'nextcore-theme'),
        'primary_mobile' => __('Inner pages mobile', 'nextcore-theme'),
        'footer_about' => __('Footer giới thiệu', 'nextcore-theme'),
        'footer_support' => __('Footer hỗ trợ', 'nextcore-theme'),
    ));
}
add_action('after_setup_theme', 'nextcore_setup');

function nextcore_widgets_init() {
    register_sidebar(array(
        'name' => __('Sidebar', 'nextcore-theme'),
        'id' => 'nextcore-sidebar',
        'before_widget' => '<section id="%1$s" class="nextcore-widget %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h2>', 'after_title' => '</h2>',
    ));
}
add_action('widgets_init', 'nextcore_widgets_init');

function nextcore_submenu_button($output, $item, $depth, $args) {
    if (empty($args->theme_location) || !in_array($args->theme_location, array('home_primary', 'home_mobile', 'primary', 'primary_mobile'), true)
        || !in_array('menu-item-has-children', (array) $item->classes, true)) {
        return $output;
    }
    $label = sprintf(__('Mở menu con: %s', 'nextcore-theme'), wp_strip_all_tags($item->title));
    return $output . '<button type="button" class="nextcore-submenu-toggle" aria-expanded="false" aria-controls="' . esc_attr(wp_unique_id('nextcore-submenu-')) . '" aria-label="' . esc_attr($label) . '" hidden><span aria-hidden="true">▾</span></button>';
}
add_filter('walker_nav_menu_start_el', 'nextcore_submenu_button', 10, 4);

function nextcore_menu_item_id($id, $item, $args) {
    return !empty($args->theme_location) ? sanitize_html_class('nextcore-' . $args->theme_location . '-item-' . $item->ID) : $id;
}
add_filter('nav_menu_item_id', 'nextcore_menu_item_id', 10, 3);
