<?php
defined('ABSPATH') || exit;

function nextcore_is_legacy_content() {
    $legacy = get_post_meta(get_the_ID(), '_elementor_edit_mode', true) === 'builder';
    return (bool) apply_filters('nextcore_is_legacy_content', $legacy, get_the_ID());
}

function nextcore_render_content() {
    echo nextcore_is_legacy_content() ? '<div class="legacy-content">' : '<div class="nextcore-native-content">';
    the_content();
    wp_link_pages();
    echo '</div>';
}

/**
 * Render the small, audited About widget vocabulary without a theme shortcode
 * engine. Keep Elementor as the body renderer and leave authored data untouched.
 */
function nextcore_about_shortcode_markup($html) {
    $tags = array('section', 'row', 'col', 'ux_image', 'gap', 'button');
    return preg_replace_callback('/' . get_shortcode_regex($tags) . '/s', function ($match) {
        $tag = $match[2];
        $attributes = shortcode_parse_atts($match[3]);
        $attributes = is_array($attributes) ? $attributes : array();
        if (($attributes['visibility'] ?? '') === 'hidden') { return ''; }
        if ($tag === 'ux_image') { return wp_get_attachment_image(absint($attributes['id'] ?? 0), 'large', false, array('loading' => 'lazy')); }
        if ($tag === 'gap') { return '<div class="nextcore-about-gap" aria-hidden="true"></div>'; }
        if ($tag === 'button') {
            $label = esc_html($attributes['text'] ?? '');
            $url = esc_url($attributes['link'] ?? '');
            return $url ? '<a class="button" href="' . $url . '">' . $label . '</a>' : $label;
        }
        $class = array('section' => 'nextcore-about-section', 'row' => 'nextcore-about-row', 'col' => 'nextcore-about-col');
        return '<div class="' . $class[$tag] . '">' . nextcore_about_shortcode_markup($match[5] ?? '') . '</div>';
    }, $html);
}

function nextcore_about_widget_compatibility($content, $widget) {
    if (is_page('ve-chung-toi') && $widget->get_name() === 'shortcode' && strpos($content, '[section') !== false) {
        return nextcore_about_shortcode_markup($content);
    }
    return $content;
}
add_filter('elementor/widget/render_content', 'nextcore_about_widget_compatibility', 20, 2);

/** The timeline repeats each item's ID on its decorative icon wrapper. */
function nextcore_timeline_widget_compatibility($content, $widget) {
    if ($widget->get_name() !== 'eae-timeline' || !class_exists('WP_HTML_Tag_Processor')) {
        return $content;
    }
    // The add-on targets icons by class; preserve item IDs and all authored data.
    $html = new WP_HTML_Tag_Processor($content);
    while ($html->next_tag(array('class_name' => 'eae-tl-icon-wrapper'))) {
        $html->remove_attribute('id');
    }
    return $html->get_updated_html();
}
add_filter('elementor/widget/render_content', 'nextcore_timeline_widget_compatibility', 20, 2);

function nextcore_builder_cached_markup_compatibility($content) {
    if (!class_exists('WP_HTML_Tag_Processor')) {
        return $content;
    }
    // Process final markup too: Elementor's document cache can bypass widget filters.
    $html = new WP_HTML_Tag_Processor($content);
    $svg_ids = array();
    while ($html->next_tag()) {
        if ($html->has_class('eae-tl-icon-wrapper')) { $html->remove_attribute('id'); }
        if ($html->get_tag() !== 'SVG') { continue; }
        $id = $html->get_attribute('id');
        if ($id && isset($svg_ids[$id]) && strpos($content, '#' . $id) === false) {
            $html->remove_attribute('id');
        }
        if ($id) { $svg_ids[$id] = true; }
    }
    return $html->get_updated_html();
}
add_filter('elementor/frontend/the_content', 'nextcore_builder_cached_markup_compatibility', 20);

/** Rebase migrated same-host asset URLs to the active WordPress directory. */
function nextcore_rebase_migrated_asset_urls($content) {
    $current_host = strtolower((string) wp_parse_url(home_url('/'), PHP_URL_HOST));
    if (!$current_host || strpos($content, '/wp-content/') === false) { return $content; }
    return preg_replace_callback(
        '~https?://([^/"\'\s<>]+)(?:/[^/"\'\s<>]+)*/wp-content/(uploads|plugins)/([^"\'\s<>)]*)~i',
        function ($match) use ($current_host) {
            if (strtolower($match[1]) !== $current_host) { return $match[0]; }
            return $match[2] === 'plugins'
                ? plugins_url('/' . $match[3])
                : content_url('/uploads/' . $match[3]);
        },
        $content
    );
}
add_filter('the_content', 'nextcore_rebase_migrated_asset_urls', 30);
add_filter('elementor/frontend/the_content', 'nextcore_rebase_migrated_asset_urls', 30);

function nextcore_lark_static_markup($content) {
    if (!is_singular('dich-vu') || get_post_field('post_name', get_queried_object_id()) !== 'lark' || !class_exists('WP_HTML_Tag_Processor')) {
        return $content;
    }
    $html = new WP_HTML_Tag_Processor($content);
    while ($html->next_tag()) {
        if ($html->get_attribute('id') === '') { $html->remove_attribute('id'); }
        // Saved carousel snapshots now render as static, accessible content.
        if ($html->has_class('slick-slide')) { $html->remove_attribute('aria-hidden'); }
    }
    return $html->get_updated_html();
}
add_filter('the_content', 'nextcore_lark_static_markup', 20);
