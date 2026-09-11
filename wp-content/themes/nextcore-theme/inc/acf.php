<?php
defined('ABSPATH') || exit;

function nextcore_acf_load_paths($paths) {
    $paths[] = get_theme_file_path('/acf-json');
    return array_unique($paths);
}
add_filter('acf/settings/load_json', 'nextcore_acf_load_paths');

function nextcore_acf_options_page() {
    if (function_exists('acf_add_options_page')) {
        acf_add_options_page(array(
            'page_title' => __('Cài đặt Nextcore', 'nextcore-theme'),
            'menu_title' => __('Nextcore', 'nextcore-theme'),
            'menu_slug' => 'nextcore-settings',
            'capability' => 'manage_options',
            'redirect' => false,
        ));
    }
}
add_action('acf/init', 'nextcore_acf_options_page');

// Only Nextcore groups save here; existing third-party groups keep their paths.
function nextcore_acf_save_paths($paths, $post) {
    return strpos($post['key'] ?? '', 'group_nc_') === 0 ? array(get_theme_file_path('/acf-json')) : $paths;
}
add_filter('acf/json/save_paths', 'nextcore_acf_save_paths', 10, 2);

function nextcore_acf_row_value($row, $parent, $name) {
    return $row['field_' . $parent . '_' . $name] ?? ($row[$name] ?? '');
}

function nextcore_acf_validate($valid, $value, $field, $input) {
    if ($valid !== true || strpos($field['key'] ?? '', 'field_nc_') !== 0) { return $valid; }
    $name = $field['_name'] ?? $field['name'];
    $type = $field['type'];
    if ($type === 'repeater' && is_array($value)) {
        $rows = array_values(array_filter($value, 'is_array'));
        if (in_array($name, array('nc_stats', 'nc_service_cards', 'nc_featured_projects'), true) && count($rows) !== 3) {
            return 'Nhóm này phải có đúng ba dòng.';
        }
        $unique = array('nc_stats' => 'metric', 'nc_technology_items' => 'mark', 'nc_featured_projects' => 'visual_variant', 'nc_social_links' => 'platform');
        $seen = array(); $objects = array();
        foreach ($rows as $row) {
            $get = function ($key) use ($row, $name) { return nextcore_acf_row_value($row, $name, $key); };
            if (isset($unique[$name])) {
                $identity = $get($unique[$name]);
                if (in_array($identity, $seen, true)) { return 'Không chọn trùng loại trong danh sách.'; }
                $seen[] = $identity;
            }
            if ($name === 'nc_service_cards') {
                $page = $get('target_page'); $term = $get('target_term'); $kind = $get('target_kind');
                if ($page && $term) { return 'Chỉ chọn một đích đến: trang hoặc danh mục. Xóa giá trị nhánh cũ trước khi đổi loại.'; }
                if ($kind === 'page' && !nextcore_published_object($page, array('page'))) { return 'Chọn trang đã xuất bản hợp lệ.'; }
                $term_object = $term ? get_term(absint($term), 'danh-muc-dich-vu') : null;
                if ($kind === 'term' && (!$term_object || is_wp_error($term_object))) { return 'Chọn danh mục dịch vụ hợp lệ.'; }
            }
            if ($name === 'nc_featured_projects') {
                $id = absint($get('object'));
                if (!$id && $get('visual_variant') !== 'portal') { return 'Olympia và Affiliate phải có đối tượng đã xuất bản.'; }
                if ($id && (!nextcore_published_object($id, array('post', 'dich-vu')) || in_array($id, $objects, true))) { return 'Dự án phải đã xuất bản, đúng loại và không trùng đối tượng.'; }
                if ($id) { $objects[] = $id; }
            }
        }
    }
    if ($value === '' || $value === null || $value === false) { return $valid; }
    if ($type === 'post_object' && !nextcore_published_object($value, $field['post_type'])) { return 'Chọn đối tượng đã xuất bản hợp lệ.'; }
    if ($type === 'image' || $type === 'file') {
        $attachment = get_post(absint($value));
        if (!$attachment || $attachment->post_type !== 'attachment') { return 'Chọn tệp trong thư viện media.'; }
        if ($type === 'image' && !wp_attachment_is_image($attachment)) { return 'Chọn tệp ảnh hợp lệ.'; }
        if ($type === 'file' && get_post_mime_type($attachment) !== 'video/mp4') { return 'Video phải là MP4.'; }
    }
    if ($type === 'select' && !array_key_exists((string) $value, $field['choices'])) { return 'Giá trị lựa chọn không hợp lệ.'; }
    if ($type === 'number' && (!is_numeric($value) || (float) $value < 0 || (float) $value != (int) $value)) { return 'Nhập số nguyên không âm.'; }
    if (substr($field['key'], -6) === '_phone' && !preg_match('/^\\+[1-9][0-9]{7,14}$/', (string) $value)) { return 'Nhập điện thoại quốc tế, ví dụ +84378962625.'; }
    if ($name === 'nc_zalo_oa_id' && !ctype_digit((string) $value)) { return 'Mã OA chỉ chứa chữ số.'; }
    if ($type === 'link' && is_array($value) && empty($value['url']) && empty($field['required'])) { return $valid; }
    if ($type === 'link' && (!is_array($value) || !preg_match('~^https?://[^\\s]+$~i', $value['url'] ?? ''))) { return 'Chọn liên kết đối tác HTTP hoặc HTTPS đầy đủ.'; }
    if ($type === 'url' && !preg_match('~^https?://[^\\s]+$~i', (string) $value)) { return 'Nhập URL HTTP hoặc HTTPS đầy đủ.'; }
    if ($type === 'wysiwyg' && preg_match('~<(script|style|iframe)\\b|\\[/?[a-zA-Z][^]]*\\]~i', (string) $value)) { return 'Không dùng script, iframe, CSS hoặc shortcode.'; }
    return $valid;
}
add_filter('acf/validate_value', 'nextcore_acf_validate', 20, 4);

function nextcore_acf_about_html($value) {
    return wp_kses($value, array('p' => array(), 'strong' => array(), 'em' => array(), 'a' => array('href' => array(), 'title' => array())));
}
add_filter('acf/update_value/key=field_nc_about_body', 'nextcore_acf_about_html');
