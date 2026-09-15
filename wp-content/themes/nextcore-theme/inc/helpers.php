<?php
defined('ABSPATH') || exit;

function nextcore_asset($path) {
    return get_theme_file_uri('/assets/' . ltrim($path, '/'));
}

function nextcore_field($name, $fallback = '', $context = null) {
    $value = function_exists('get_field') ? get_field($name, $context === null ? get_queried_object_id() : $context) : null;
    return is_scalar($value) && $value !== false && $value !== '' ? $value : $fallback;
}

function nextcore_option($name, $fallback = '') {
    return nextcore_field($name, $fallback, 'option');
}

function nextcore_rows($name, $context = null) {
    $value = function_exists('get_field') ? get_field($name, $context === null ? get_queried_object_id() : $context) : null;
    if (is_array($value)) {
        return array_values(array_filter($value, 'is_array'));
    }
    // A deliberately empty optional repeater must not restore bundled rows.
    if (function_exists('get_field')) {
        $source = $context === null ? get_queried_object_id() : $context;
        $configured = in_array($source, array('option', 'options'), true)
            ? get_option('options_' . $name, null) !== null
            : metadata_exists('post', $source, $name);
        if ($configured) { return array(); }
    }
    $defaults = nextcore_home_defaults();
    return $defaults[$name] ?? array();
}

function nextcore_published_object($id, $types = array('page', 'post', 'dich-vu')) {
    if (!absint($id)) { return null; }
    $post = get_post(absint($id));
    return $post && $post->post_status === 'publish' && in_array($post->post_type, $types, true) ? $post : null;
}

function nextcore_page_url($slug) {
    $page = get_page_by_path($slug, OBJECT, 'page');
    return $page && $page->post_status === 'publish' ? nextcore_localized_url(get_permalink($page)) : '';
}

function nextcore_contact_url() {
    $id = absint(nextcore_option('nc_contact_page', 0));
    $page = $id ? nextcore_published_object($id, array('page')) : null;
    return $page ? nextcore_localized_url(get_permalink($page)) : nextcore_page_url('lien-he');
}

function nextcore_service_url($row) {
    if (($row['target_kind'] ?? '') === 'page') {
        if (!empty($row['target_term'])) { return ''; }
        if (!empty($row['target_page'])) {
            $page = nextcore_published_object($row['target_page'], array('page'));
            return $page ? nextcore_localized_url(get_permalink($page)) : '';
        }
        return nextcore_page_url($row['target_slug'] ?? 'outsource');
    }
    if (!empty($row['target_page'])) { return ''; }
    $term = !empty($row['target_term']) ? get_term(absint($row['target_term']), 'danh-muc-dich-vu') : get_term_by('slug', $row['target_slug'] ?? '', 'danh-muc-dich-vu');
    if (!$term || is_wp_error($term)) { return ''; }
    $url = get_term_link($term);
    return is_wp_error($url) ? '' : nextcore_localized_url($url);
}

function nextcore_project_object($row) {
    if (!empty($row['object'])) {
        return nextcore_published_object($row['object'], array('post', 'dich-vu'));
    }
    // Only the two audited Post slugs have a safe lookup; Portal waits for its relationship.
    $slug = $row['target_slug'] ?? '';
    if (!in_array($slug, array('truong-doanh-nhan-top-olympia', 'wordpress-plugin-affiliate'), true)) { return null; }
    $post = get_page_by_path($slug, OBJECT, 'post');
    return $post ? nextcore_published_object($post->ID, array('post')) : null;
}

function nextcore_image_url($value) {
    if (is_numeric($value)) {
        return wp_get_attachment_image_url(absint($value), 'full') ?: '';
    }
    return is_string($value) && preg_match('~^images/[a-zA-Z0-9_.-]+$~', $value) ? nextcore_asset($value) : '';
}

function nextcore_image($value, $alt = '', $class = '', $width = 0, $height = 0) {
    if (is_numeric($value) && absint($value)) {
        echo wp_get_attachment_image(absint($value), 'full', false, array('class' => $class, 'alt' => $alt, 'loading' => 'lazy'));
        return;
    }
    $url = nextcore_image_url($value);
    if (!$url) { return; }
    printf('<img src="%s" class="%s" alt="%s" width="%d" height="%d" loading="lazy">', esc_url($url), esc_attr($class), esc_attr($alt), absint($width), absint($height));
}

function nextcore_mode_url($field, $fallback, $video = false) {
    $id = absint(nextcore_option($field, 0));
    $url = $id ? ($video ? wp_get_attachment_url($id) : wp_get_attachment_image_url($id, 'full')) : '';
    return $url ?: nextcore_asset($fallback);
}

function nextcore_mode_image($kind, $class, $alt, $width, $height) {
    $files = array(
        'hero' => array('home/hero-platform-network-dark-sharp.webp', 'home/hero-platform-network-light-sharp.webp'),
        'cta' => array('cta.png', 'cta-light.png'),
        'about' => array('danang-poster.jpg', 'cauronglight.png'),
    );
    $base = $kind === 'about' ? 'nc_about_poster_' : 'nc_' . $kind . '_image_';
    $dark = nextcore_mode_url($base . 'dark', 'images/' . $files[$kind][0]);
    $light = nextcore_mode_url($base . 'light', 'images/' . $files[$kind][1]);
    // Replace the imported corporate defaults while preserving custom ACF images.
    if ($kind === 'hero') {
        foreach (array('dark' => 0, 'light' => 1) as $mode => $index) {
            if (preg_match('/^hero-corporate-(?:v2|light)(?:-\d+)?\.(?:png|webp|jpe?g)$/i', wp_basename(wp_parse_url($$mode, PHP_URL_PATH)))) {
                $$mode = nextcore_asset('images/' . $files[$kind][$index]);
            }
        }
    }
    ?>
    <img class="<?php echo esc_attr($class); ?> mode-image" data-mode-image data-dark-src="<?php echo esc_url($dark); ?>" data-light-src="<?php echo esc_url($light); ?>" alt="<?php echo esc_attr($alt); ?>" width="<?php echo absint($width); ?>" height="<?php echo absint($height); ?>" <?php if ($kind === 'hero') : ?>fetchpriority="high"<?php endif; ?>>
    <noscript><picture><source media="(prefers-color-scheme: light)" srcset="<?php echo esc_url($light); ?>"><img class="<?php echo esc_attr($class); ?>" src="<?php echo esc_url($dark); ?>" alt="<?php echo esc_attr($alt); ?>" width="<?php echo absint($width); ?>" height="<?php echo absint($height); ?>"></picture></noscript>
    <?php
}

function nextcore_is_development() {
    return in_array(wp_get_environment_type(), array('local', 'development'), true) || (defined('NEXTCORE_DEVELOPMENT_PREVIEW') && NEXTCORE_DEVELOPMENT_PREVIEW);
}

function nextcore_menu_fallback($args) {
    if (!nextcore_is_development()) { return; }
    $items = array('about' => __('Giới thiệu', 'nextcore-theme'), 'services' => __('Dịch vụ', 'nextcore-theme'), 'technology' => __('Công nghệ', 'nextcore-theme'), 'projects' => __('Sản phẩm / Dự án', 'nextcore-theme'));
    if (strpos($args['theme_location'], 'mobile') !== false) {
        $items['partner'] = __('Đối tác', 'nextcore-theme');
        $items['testimonials'] = __('Đánh giá', 'nextcore-theme');
    }
    $items += array('team' => __('Đội ngũ', 'nextcore-theme'), 'blog' => __('Tin tức', 'nextcore-theme'), 'contact' => __('Liên hệ', 'nextcore-theme'));
    if ($args['theme_location'] === 'footer_about') { $items = array('about' => __('Giới thiệu', 'nextcore-theme'), 'team' => __('Đội ngũ', 'nextcore-theme'), 'projects' => __('Dự án', 'nextcore-theme')); }
    if ($args['theme_location'] === 'footer_support') { $items = array('blog' => __('Tin tức', 'nextcore-theme'), 'services' => __('Dịch vụ', 'nextcore-theme'), 'contact' => __('Liên hệ', 'nextcore-theme')); }
    echo '<ul class="' . esc_attr($args['menu_class']) . '">';
    foreach ($items as $fragment => $label) {
        echo '<li><a href="' . esc_url(nextcore_home_url($fragment)) . '">' . esc_html($label) . '</a></li>';
    }
    echo '</ul>';
}

function nextcore_navigation($mobile = false) {
    $location = is_front_page() ? ($mobile ? 'home_mobile' : 'home_primary') : ($mobile ? 'primary_mobile' : 'primary');
    wp_nav_menu(array(
        'theme_location' => $location, 'container' => false,
        'menu_id' => $mobile ? 'nextcore-mobile-menu' : 'nextcore-desktop-menu',
        'menu_class' => 'nextcore-menu', 'fallback_cb' => 'nextcore_menu_fallback',
    ));
}
