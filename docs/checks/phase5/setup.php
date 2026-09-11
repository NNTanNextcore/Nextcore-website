<?php
/**
 * Manual local setup. Default: dry-run. Apply: php setup.php --apply-local
 * Never included by the theme, activation hooks, or public requests.
 */
require __DIR__ . '/bootstrap.php';
$apply = in_array('--apply-local', $argv, true);
// Seeding a reference must not re-parent an existing Media Library attachment.
add_filter('acf/connect_attachment_to_post', '__return_false');
$report = array('mode' => $apply ? 'apply-local' : 'dry-run', 'started_utc' => gmdate('c'), 'media' => array(), 'acf' => array(), 'menus' => array());
function nc5_json($path, $data) {
    if (file_put_contents($path, wp_json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)) === false) {
        throw new RuntimeException('Cannot write review artifact: ' . basename($path));
    }
}
function nc5_write($callback) {
    $GLOBALS['nc5_write_scope'] = true;
    try { return $callback(); } finally { $GLOBALS['nc5_write_scope'] = false; }
}
function nc5_object($slug, $type) {
    $post = get_page_by_path($slug, OBJECT, $type);
    if (!$post || $post->post_status !== 'publish') { throw new RuntimeException('Missing published object: ' . $slug); }
    return (int) $post->ID;
}
function nc5_term($slug, $taxonomy) {
    $term = get_term_by('slug', $slug, $taxonomy);
    if (!$term || is_wp_error($term)) { throw new RuntimeException('Missing term: ' . $slug); }
    return (int) $term->term_id;
}
function nc5_snapshot() {
    global $wpdb;
    $tables = array($wpdb->options => 'option_id', $wpdb->posts => 'ID', $wpdb->postmeta => 'meta_id', $wpdb->terms => 'term_id', $wpdb->term_taxonomy => 'term_taxonomy_id', $wpdb->term_relationships => null, $wpdb->termmeta => 'meta_id');
    $out = array();
    foreach ($tables as $table => $key) {
        $out[$table] = array();
        foreach ($wpdb->get_results("SELECT * FROM $table", ARRAY_A) as $row) {
            $id = $key ? $row[$key] : $row['object_id'] . ':' . $row['term_taxonomy_id'];
            $out[$table][$id] = $row;
        }
    }
    return $out; // Kept in process memory only; no unrelated settings exported.
}
function nc5_diff($before, $after) {
    $diff = array();
    foreach ($after as $table => $rows) {
        foreach ($rows as $id => $row) {
            if (!isset($before[$table][$id]) || $before[$table][$id] !== $row) {
                $diff[] = array('table' => $table, 'id' => $id, 'operation' => isset($before[$table][$id]) ? 'updated' : 'created', 'before' => $before[$table][$id] ?? null, 'after' => $row);
            }
        }
        foreach ($before[$table] as $id => $row) {
            if (!isset($rows[$id])) { $diff[] = array('table' => $table, 'id' => $id, 'operation' => 'deleted', 'before' => $row, 'after' => null); }
        }
    }
    return $diff;
}
function nc5_scalar_defaults() {
    $values = array();
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(get_theme_file_path(), FilesystemIterator::SKIP_DOTS));
    foreach ($iterator as $file) {
        if ($file->getExtension() !== 'php') { continue; }
        $tokens = array_values(array_filter(token_get_all(file_get_contents($file->getPathname())), function ($t) {
            return !is_array($t) || !in_array($t[0], array(T_WHITESPACE, T_COMMENT, T_DOC_COMMENT), true);
        }));
        foreach ($tokens as $i => $t) {
            if (!is_array($t) || $t[0] !== T_STRING || !in_array($t[1], array('nextcore_field', 'nextcore_option'), true)) { continue; }
            if (($tokens[$i + 1] ?? '') !== '(' || ($tokens[$i + 3] ?? '') !== ',') { continue; }
            $a = $tokens[$i + 2] ?? null; $b = $tokens[$i + 4] ?? null;
            if (!is_array($a) || !is_array($b) || $a[0] !== T_CONSTANT_ENCAPSED_STRING || $b[0] !== T_CONSTANT_ENCAPSED_STRING) { continue; }
            $decode = function ($literal) {
                $body = substr($literal, 1, -1);
                return $literal[0] === "'" ? str_replace(array("\\\\", "\\'"), array("\\", "'"), $body) : stripcslashes($body);
            };
            $values[$decode($a[1])] = $decode($b[1]);
        }
    }
    return $values;
}

$frontpage = (int) get_option('page_on_front');
if (!nextcore_published_object($frontpage, array('page'))) { throw new RuntimeException('No published front page.'); }
$resolved = array(
    'frontpage' => $frontpage, 'contact' => nc5_object('lien-he', 'page'),
    'outsource' => nc5_object('outsource', 'page'),
    'olympia' => nc5_object('truong-doanh-nhan-top-olympia', 'post'),
    'affiliate' => nc5_object('wordpress-plugin-affiliate', 'post'),
    'consulting' => nc5_term('tu-van-doanh-nghiep', 'danh-muc-dich-vu'),
    'personal' => nc5_term('khach-hang-ca-nhan', 'danh-muc-dich-vu'),
    'products' => nc5_term('san-pham', 'danh-muc-dich-vu'),
    'knowledge' => nc5_term('kien-thuc', 'category'),
    'company' => nc5_term('cong-ty', 'category'),
    'portal' => null,
);
$report['resolved'] = $resolved;
$groups = array_values(array_filter(acf_get_field_groups(), function ($g) { return strpos($g['key'], 'group_nc_') === 0; }));
if (count($groups) !== 15) { throw new RuntimeException('Expected exactly 15 JSON field groups.'); }
$values = array_merge(nc5_scalar_defaults(), nextcore_home_defaults(), array(
    'nc_footer_about_heading' => 'Về Nextcore', 'nc_footer_support_heading' => 'Hỗ trợ',
    'nc_contact_page' => $resolved['contact'],
    'nc_partner_link' => array('title' => 'Tìm hiểu thêm', 'url' => 'https://gm-group.vn/', 'target' => ''),
    'nc_hero_image_dark' => 'images/hero-corporate-v2.png',
    'nc_hero_image_light' => 'images/hero-corporate-light.png',
    'nc_about_video_dark' => 'video/nextcore-danang.mp4',
    'nc_about_video_light' => 'video/caurongquay-light-video.mp4',
    'nc_about_poster_dark' => 'images/danang-poster.jpg',
    'nc_about_poster_light' => 'images/cauronglight.png',
    'nc_cta_image_dark' => 'images/cta.png', 'nc_cta_image_light' => 'images/cta-light.png',
));
$values['nc_service_cards'][0]['target_page'] = $resolved['outsource'];
$values['nc_service_cards'][1]['target_term'] = $resolved['consulting'];
$values['nc_service_cards'][2]['target_term'] = $resolved['personal'];
$values['nc_featured_projects'][0]['object'] = '';
$values['nc_featured_projects'][1]['object'] = $resolved['olympia'];
$values['nc_featured_projects'][2]['object'] = $resolved['affiliate'];
// Public integration identifiers stay empty until a separate integration QA.
foreach (array('nc_messenger_url', 'nc_zalo_oa_id', 'nc_zalo_welcome', 'nc_umami_script_url', 'nc_umami_website_id') as $name) { $values[$name] = ''; }
$media_paths = array();
$collect = function ($value) use (&$collect, &$media_paths) {
    if (is_array($value)) { foreach ($value as $v) { $collect($v); } }
    elseif (is_string($value) && preg_match('~^(images|video)/[^/]+\\.(png|jpg|webp|mp4)$~', $value)) { $media_paths[$value] = true; }
};
$collect($values);
// Check full attachment file and preserved original; filename alone is not proof.
$attachment_index = array();
foreach ($wpdb->get_col("SELECT ID FROM {$wpdb->posts} WHERE post_type='attachment' ORDER BY ID") as $id) {
    $file = get_attached_file($id);
    $candidates = array($file);
    $meta = wp_get_attachment_metadata($id);
    if (!empty($meta['original_image']) && $file) { $candidates[] = dirname($file) . '/' . $meta['original_image']; }
    foreach ($candidates as $candidate) {
        if ($candidate && is_file($candidate)) {
            $hash = hash_file('sha256', $candidate);
            $attachment_index[$hash][] = array('id' => (int) $id, 'file' => $candidate, 'full' => $candidate === $file);
        }
    }
}
$media_map = array();
foreach (array_keys($media_paths) as $path) {
    $source = get_theme_file_path('/assets/' . $path);
    if (!is_file($source)) { throw new RuntimeException('Missing approved asset: ' . $path); }
    $hash = hash_file('sha256', $source);
    $matches = $attachment_index[$hash] ?? array();
    // Full-size equivalence preserves sharpness; original-only matches are flagged, never re-imported.
    $match = $matches ? $matches[0] : null;
    foreach ($matches as $candidate) { if ($candidate['full']) { $match = $candidate; break; } }
    if ($match && !$match['full']) { throw new RuntimeException('Original-only equivalent attachment needs reconciliation: ' . $path); }
    $media_map[$path] = $match ? $match['id'] : 0;
    $report['media'][$path] = array('sha256' => $hash, 'filename' => basename($source), 'action' => $match ? 'reuse' : 'import', 'attachment_id' => $media_map[$path]);
}
$before = nc5_snapshot();
$journal = __DIR__ . '/local-write-' . gmdate('Ymd-His') . '.json';
if ($apply) {
    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/media.php';
    require_once ABSPATH . 'wp-admin/includes/image.php';
    add_filter('big_image_size_threshold', '__return_false');
    // Keep byte-identical originals; intermediate sizes are generated by WordPress.
    register_shutdown_function(function () use (&$report, &$before, $journal) {
        $GLOBALS['nc5_write_scope'] = false;
        $report['database_diff'] = nc5_diff($before, nc5_snapshot());
        $report['write_query_counts'] = $GLOBALS['nc5_write_counts'] ?? array();
        $report['finished_utc'] = gmdate('c');
        nc5_json($journal, $report);
    });
    foreach ($media_map as $path => $id) {
        if ($id) { continue; }
        $source = get_theme_file_path('/assets/' . $path);
        $upload = wp_upload_dir();
        if ($upload['error']) { throw new RuntimeException('Local uploads directory unavailable.'); }
        $filename = wp_unique_filename($upload['path'], basename($source));
        $destination = $upload['path'] . '/' . $filename;
        if (!copy($source, $destination) || hash_file('sha256', $destination) !== $report['media'][$path]['sha256']) { throw new RuntimeException('Asset copy verification failed.'); }
        $id = nc5_write(function () use ($destination, $filename, $upload) {
            $id = wp_insert_attachment(array('post_title' => pathinfo($filename, PATHINFO_FILENAME), 'post_status' => 'inherit', 'post_mime_type' => wp_check_filetype($filename)['type'], 'guid' => $upload['url'] . '/' . $filename), $destination, 0, true);
            if (is_wp_error($id)) { throw new RuntimeException($id->get_error_message()); }
            wp_update_attachment_metadata($id, wp_generate_attachment_metadata($id, $destination));
            return (int) $id;
        });
        $media_map[$path] = $id;
        $report['media'][$path]['attachment_id'] = $id;
    }
}
$map_value = function ($field, $value) use (&$map_value, $media_map) {
    if (in_array($field['type'], array('image', 'file'), true)) { return $media_map[$value] ?? $value; }
    if ($field['type'] !== 'repeater') { return $value; }
    $result = array();
    foreach ($value as $row) {
        $clean = array();
        foreach ($field['sub_fields'] as $sub) { $clean[$sub['name']] = $map_value($sub, $row[$sub['name']] ?? ''); }
        $result[] = $clean;
    }
    return $result;
};
foreach ($groups as $group) {
    $context = $group['location'][0][0]['param'] === 'options_page' ? 'option' : $frontpage;
    foreach (acf_get_fields($group) as $field) {
        $name = $field['name'];
        if (!array_key_exists($name, $values)) { throw new RuntimeException('No reviewed seed value for ' . $name); }
        $value = $map_value($field, $values[$name]);
        $exists = $context === 'option' ? get_option('options_' . $name, null) !== null : metadata_exists('post', $context, $name);
        $entry = array('context' => $context, 'field_key' => $field['key'], 'action' => $exists ? 'preserve-existing' : 'create', 'value' => $exists ? get_field($field['key'], $context) : $value);
        if (!$exists && $apply) {
            acf_reset_validation_errors();
            $validate_value = $value;
            if ($field['type'] === 'repeater') {
                $validate_value = array();
                foreach ($value as $row) {
                    $keyed = array();
                    foreach ($field['sub_fields'] as $sub) {
                        if ($name === 'nc_service_cards' && (($sub['name'] === 'target_page' && $row['target_kind'] !== 'page') || ($sub['name'] === 'target_term' && $row['target_kind'] !== 'term'))) { continue; }
                        $keyed[$sub['key']] = $row[$sub['name']];
                    }
                    $validate_value[] = $keyed;
                }
            }
            acf_validate_value($validate_value, $field, $field['key']);
            if (acf_get_validation_errors()) { throw new RuntimeException('Invalid seed: ' . $name . ' ' . wp_json_encode(acf_get_validation_errors())); }
            $stored_value = $field['type'] === 'date_picker' ? str_replace('-', '', $value) : $value;
            nc5_write(function () use ($field, $stored_value, $context) { update_field($field['key'], $stored_value, $context); });
        }
        $report['acf'][$name] = $entry;
    }
}
$base = array(
    array('about', '', 'Giới thiệu', 'custom', '#about'),
    array('services', '', 'Dịch vụ', 'custom', '#services'),
    array('outsource', 'services', 'Outsource', 'page', $resolved['outsource']),
    array('consulting', 'services', 'Tư vấn doanh nghiệp', 'danh-muc-dich-vu', $resolved['consulting']),
    array('personal', 'services', 'Khách hàng cá nhân', 'danh-muc-dich-vu', $resolved['personal']),
    array('products', 'services', 'Sản phẩm', 'danh-muc-dich-vu', $resolved['products']),
    array('technology', '', 'Công nghệ', 'custom', '#technology'),
    array('projects', '', 'Sản phẩm / Dự án', 'custom', '#projects'),
);
$tail = array(
    array('team', '', 'Đội ngũ', 'custom', '#team'),
    array('blog', '', 'Tin tức', 'custom', '#blog'),
    array('knowledge', 'blog', 'Kiến thức', 'category', $resolved['knowledge']),
    array('company', 'blog', 'Công ty', 'category', $resolved['company']),
    array('contact', '', 'Liên hệ', 'custom', '#contact'),
);
$locations = get_theme_mod('nav_menu_locations', array());
foreach (array('home_primary' => 'Nextcore Homepage Desktop', 'home_mobile' => 'Nextcore Homepage Mobile') as $location => $title) {
    $rows = $base;
    if ($location === 'home_mobile') {
        $rows[] = array('partner', '', 'Đối tác', 'custom', '#partner');
        $rows[] = array('testimonials', '', 'Đánh giá', 'custom', '#testimonials');
    }
    $rows = array_merge($rows, $tail);
    $menu = wp_get_nav_menu_object($title);
    $menu_id = $menu ? (int) $menu->term_id : 0;
    if (!$menu_id && $apply) {
        $menu_id = nc5_write(function () use ($title) { return wp_create_nav_menu($title); });
        if (is_wp_error($menu_id)) { throw new RuntimeException($menu_id->get_error_message()); }
    }
    $existing = $menu_id ? wp_get_nav_menu_items($menu_id) : array();
    $item_ids = array();
    foreach ($existing ?: array() as $item) {
        $key = get_post_meta($item->ID, '_nextcore_setup_key', true);
        if (!$key) { throw new RuntimeException('Unmanaged menu with setup name; refusing to modify it.'); }
        $item_ids[$key] = (int) $item->ID;
    }
    $report['menus'][$location] = array('name' => $title, 'id' => $menu_id, 'action' => $menu ? 'reuse' : 'create', 'items' => array());
    foreach ($rows as $index => $row) {
        list($key, $parent, $label, $kind, $target) = $row;
        $args = array('menu-item-title' => $label, 'menu-item-status' => 'publish', 'menu-item-position' => $index + 1, 'menu-item-parent-id' => $parent ? ($item_ids[$parent] ?? 0) : 0);
        if ($kind === 'custom') { $args += array('menu-item-type' => 'custom', 'menu-item-url' => $target); }
        else { $args += array('menu-item-type' => $kind === 'page' ? 'post_type' : 'taxonomy', 'menu-item-object' => $kind, 'menu-item-object-id' => $target); }
        $id = $item_ids[$key] ?? 0;
        $action = $id ? 'preserve-existing' : 'create';
        if (!$id && $apply) {
            $id = nc5_write(function () use ($menu_id, $args, $key) {
                $id = wp_update_nav_menu_item($menu_id, 0, $args);
                if (is_wp_error($id)) { throw new RuntimeException($id->get_error_message()); }
                update_post_meta($id, '_nextcore_setup_key', $key);
                return (int) $id;
            });
        }
        $item_ids[$key] = $id;
        $report['menus'][$location]['items'][] = array('id' => $id, 'action' => $action, 'args' => $args);
    }
    if (!empty($locations[$location]) && (int) $locations[$location] !== (int) $menu_id) { throw new RuntimeException('Existing location assignment differs; refusing overwrite.'); }
    if ($menu_id) { $locations[$location] = (int) $menu_id; }
}
if ($apply && get_theme_mod('nav_menu_locations', array()) !== $locations) {
    nc5_write(function () use ($locations) { set_theme_mod('nav_menu_locations', $locations); });
}
$report['assignments_after'] = $locations;
$report['stored_stylesheet'] = $wpdb->get_var("SELECT option_value FROM {$wpdb->options} WHERE option_name='stylesheet'");
$report['complete'] = true;
if (!$apply) { nc5_json(__DIR__ . '/dry-run.json', $report); }
echo wp_json_encode(array('mode' => $report['mode'], 'acf_fields' => count($report['acf']), 'media' => count($report['media']), 'imports' => count(array_filter($report['media'], function ($m) { return $m['action'] === 'import'; })), 'report' => $apply ? basename($journal) : 'dry-run.json'), JSON_PRETTY_PRINT);
