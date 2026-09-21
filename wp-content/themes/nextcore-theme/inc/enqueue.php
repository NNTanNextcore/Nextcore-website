<?php
defined('ABSPATH') || exit;

function nextcore_asset_version($path) {
    $file = get_theme_file_path($path);
    return is_file($file) ? (string) filemtime($file) : '0.2.0';
}

function nextcore_theme_init_script() {
    $names = is_front_page() ? array('theme-init', 'media') : array('theme-init');
    foreach ($names as $name) {
        $file = get_theme_file_path('/assets/js/' . $name . '.js');
        if (is_readable($file)) { wp_print_inline_script_tag(file_get_contents($file), array('id' => 'nextcore-' . $name)); }
    }
}
add_action('wp_head', 'nextcore_theme_init_script', 0);

function nextcore_enqueue_assets() {
    $styles = array('tokens', 'base', 'layout');
    if (is_front_page()) { $styles = array_merge($styles, array('services', 'projects', 'partner', 'testimonials', 'home-section-snap')); }
    $styles = array_merge($styles, array('light', 'content', 'compatibility', 'native', 'custom'));
    $previous = array();
    foreach ($styles as $name) {
        $path = '/assets/css/' . $name . '.css';
        $handle = 'nextcore-' . $name;
        wp_enqueue_style($handle, get_theme_file_uri($path), $previous, nextcore_asset_version($path));
        $previous = array($handle);
    }
    if (is_front_page() && class_exists('Nextcore_Team_Renderer') && wp_style_is('nextcore-team', 'registered')) {
        $team_settings = Nextcore_Team_Renderer::defaults();
        wp_enqueue_style('nextcore-team');
        if (!empty($team_settings['custom_css'])) { wp_add_inline_style('nextcore-team', $team_settings['custom_css']); }
    }
    $scripts = array('theme-switch', 'navigation');
    if (is_front_page()) { $scripts = array_merge($scripts, array('home', 'testimonials', 'home-section-snap')); }
    foreach ($scripts as $name) {
        $path = '/assets/js/' . $name . '.js';
        wp_enqueue_script('nextcore-' . $name, get_theme_file_uri($path), array(), nextcore_asset_version($path), true);
    }
    if (is_singular() && comments_open() && get_option('thread_comments')) { wp_enqueue_script('comment-reply'); }
}
add_action('wp_enqueue_scripts', 'nextcore_enqueue_assets');

function nextcore_enqueue_elementor_footer_assets() {
    if (!did_action('elementor/loaded') || !defined('ELEMENTOR_ASSETS_URL') || !defined('ELEMENTOR_VERSION')) {
        return;
    }

    wp_enqueue_style(
        'widget-social-icons',
        ELEMENTOR_ASSETS_URL . 'css/widget-social-icons.min.css',
        array('elementor-frontend'),
        ELEMENTOR_VERSION
    );
}
add_action('wp_enqueue_scripts', 'nextcore_enqueue_elementor_footer_assets', 20);

function nextcore_body_classes($classes) {
    $classes[] = 'nextcore-site';
    if (is_front_page()) { $classes[] = 'nextcore-homepage'; }
    return $classes;
}
add_filter('body_class', 'nextcore_body_classes');

