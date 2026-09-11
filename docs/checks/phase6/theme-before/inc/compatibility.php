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

