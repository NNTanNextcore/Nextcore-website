<?php
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
define('DISABLE_WP_CRON', true);
$_SERVER['HTTP_HOST'] = 'localhost';
$_SERVER['REQUEST_URI'] = '/';
$_SERVER['REQUEST_METHOD'] = 'GET';
$GLOBALS['wp_filter']['query'][1][] = array('function' => function ($sql) {
    return preg_match('/^\s*(SELECT|SHOW|DESCRIBE|EXPLAIN)\b/i', $sql) ? $sql : 'SELECT NULL WHERE 1=0';
}, 'accepted_args' => 1);
ob_start();
require dirname(__DIR__, 3) . '/wp-load.php';
if (!in_array(DB_HOST, array('localhost', '127.0.0.1', '::1'), true)) { throw new RuntimeException('Local database required.'); }
$data = array('captured_utc' => gmdate('c'), 'theme' => get_option('stylesheet'), 'template' => get_option('template'), 'theme_mods' => get_theme_mods(), 'frontpage' => get_option('page_on_front'), 'posts_page' => get_option('page_for_posts'), 'plugins' => get_option('active_plugins'), 'permalink' => get_option('permalink_structure'));
$data['runtime'] = array('acf' => defined('ACF_VERSION') ? ACF_VERSION : false, 'elementor' => defined('ELEMENTOR_VERSION') ? ELEMENTOR_VERSION : false, 'elementor_pro' => defined('ELEMENTOR_PRO_VERSION') ? ELEMENTOR_PRO_VERSION : false, 'cf7' => defined('WPCF7_VERSION') ? WPCF7_VERSION : false, 'translatepress' => defined('TRP_PLUGIN_VERSION') ? TRP_PLUGIN_VERSION : class_exists('TRP_Translate_Press'), 'dich_vu' => post_type_exists('dich-vu'), 'taxonomy' => taxonomy_exists('danh-muc-dich-vu'));
$data['acf_options'] = $wpdb->get_results("SELECT option_name,option_value,autoload FROM {$wpdb->options} WHERE option_name REGEXP '^_?options_nc_'", ARRAY_A);
$data['frontpage_acf'] = $wpdb->get_results($wpdb->prepare("SELECT meta_key,meta_value FROM {$wpdb->postmeta} WHERE post_id=%d AND meta_key REGEXP '^_?nc_'", $data['frontpage']), ARRAY_A);
$data['menus'] = array();
foreach (wp_get_nav_menus() as $menu) { $data['menus'][] = array('term' => $menu, 'items' => wp_get_nav_menu_items($menu->term_id)); }
$data['dictionaries'] = array();
foreach ($wpdb->get_col("SHOW TABLES LIKE '{$wpdb->prefix}trp_%'") as $table) {
    $data['dictionary_counts'][$table] = (int) $wpdb->get_var("SELECT COUNT(*) FROM $table");
    if (strpos($table, 'dictionary_vi_en_us') !== false || strpos($table, 'gettext_en_us') !== false) { $data['dictionaries'][$table] = $wpdb->get_results("SELECT * FROM $table", ARRAY_A); }
}
$data['content'] = $wpdb->get_results("SELECT ID,post_type,post_status,post_name,post_title,post_content,post_excerpt,post_parent FROM {$wpdb->posts} WHERE post_type IN ('page','post','dich-vu') AND post_status IN ('publish','draft')", ARRAY_A);
$data['legacy_meta'] = $wpdb->get_results("SELECT post_id,meta_key,meta_value FROM {$wpdb->postmeta} WHERE meta_key IN ('_elementor_data','_elementor_edit_mode','_wp_page_template','custom_css','gallery','_thumbnail_id')", ARRAY_A);
while (ob_get_level()) { ob_end_clean(); }
$snapshot_name = $argv[1] ?? 'current-state-before';
if (!preg_match('/^[a-z0-9-]+$/', $snapshot_name)) { throw new RuntimeException('Invalid snapshot name.'); }
file_put_contents(__DIR__ . '/' . $snapshot_name . '.json', wp_json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
echo wp_json_encode(array_diff_key($data, array_flip(array('acf_options','frontpage_acf','menus','dictionaries','content','legacy_meta'))), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
