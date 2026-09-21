<?php
defined('ABSPATH') || exit;

/**
 * TranslatePress public URL converter; see docs/dependencies.md.
 * Missing/incomplete plugin falls back to the WordPress URL.
 */
function nextcore_localized_url($url) {
    if (!is_callable(array('TRP_Translate_Press', 'get_trp_instance'))) {
        return $url;
    }
    $plugin = TRP_Translate_Press::get_trp_instance();
    if (!is_object($plugin) || !is_callable(array($plugin, 'get_component'))) {
        return $url;
    }
    $converter = $plugin->get_component('url_converter');
    if (!is_object($converter) || !is_callable(array($converter, 'get_url_for_language'))) {
        return $url;
    }
    $translated = $converter->get_url_for_language(null, $url, '');
    return is_string($translated) && $translated !== '' ? $translated : $url;
}

function nextcore_home_url($fragment = '') {
    $url = nextcore_localized_url(home_url('/'));
    return $fragment === '' ? $url : $url . '#' . sanitize_title(ltrim($fragment, '#'));
}

function nextcore_menu_anchor($atts, $item, $args) {
    $locations = array('home_primary', 'home_mobile', 'primary', 'primary_mobile', 'footer_about', 'footer_support', 'footer_nextcore', 'footer_services', 'footer_explore', 'footer_connect');
    if (isset($args->theme_location, $atts['href']) && in_array($args->theme_location, $locations, true)
        && strpos($atts['href'], '#') === 0 && strlen($atts['href']) > 1) {
        $atts['href'] = is_front_page() ? $atts['href'] : nextcore_home_url($atts['href']);
    }
    return $atts;
}
add_filter('nav_menu_link_attributes', 'nextcore_menu_anchor', 10, 3);

function nextcore_language_links() {
    return function_exists('trp_custom_language_switcher') ? (array) trp_custom_language_switcher() : array();
}

/**
 * Load the theme catalog selected by TranslatePress.
 *
 * TranslatePress keeps the WordPress locale unchanged on translated URLs, so
 * load_theme_textdomain() alone cannot select the matching theme MO file.
 */
function nextcore_load_current_language_catalog() {
    if (is_admin() || !function_exists('nextcore_current_language')) {
        return;
    }

    $locale = nextcore_current_language();
    if ($locale === '' || $locale === 'vi' || strpos($locale, 'vi_') === 0) {
        return;
    }

    $catalog = get_theme_file_path('/languages/nextcore-theme-' . sanitize_file_name($locale) . '.mo');
    if (!is_readable($catalog)) {
        return;
    }

    unload_textdomain('nextcore-theme');
    load_textdomain('nextcore-theme', $catalog);
}
add_action('wp', 'nextcore_load_current_language_catalog', 1);

