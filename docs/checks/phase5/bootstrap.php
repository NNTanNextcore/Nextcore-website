<?php
/** CLI-only, request-local Nextcore context. Stored theme/plugin settings are untouched. */
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
define('WP_USE_THEMES', false);
define('DISABLE_WP_CRON', true);
define('NEXTCORE_DEVELOPMENT_PREVIEW', true);
$_SERVER['HTTP_HOST'] = 'localhost';
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['REQUEST_URI'] = '/';
$_SERVER['SERVER_PROTOCOL'] = 'HTTP/1.1';
function nc5_hook($name, $callback, $args = 1) {
    $GLOBALS['wp_filter'][$name][1][] = array('function' => $callback, 'accepted_args' => $args);
}
nc5_hook('pre_option_template', function () { return 'nextcore-theme'; });
nc5_hook('pre_option_stylesheet', function () { return 'nextcore-theme'; });
nc5_hook('pre_option_home', function () { return 'http://localhost'; });
nc5_hook('pre_option_siteurl', function () { return 'http://localhost'; });
nc5_hook('pre_option_active_plugins', function () { return array('advanced-custom-fields-pro/acf.php'); });
nc5_hook('pre_site_option_active_sitewide_plugins', function () { return array(); });
nc5_hook('query', function ($sql) {
    if (preg_match('/^\\s*(SELECT|SHOW|DESCRIBE|EXPLAIN)\\b/i', $sql)) { return $sql; }
    $wpdb = $GLOBALS['wpdb'] ?? null;
    $allowed = $wpdb ? array($wpdb->posts, $wpdb->postmeta, $wpdb->options, $wpdb->terms, $wpdb->term_taxonomy, $wpdb->term_relationships, $wpdb->termmeta) : array();
    if (!empty($GLOBALS['nc5_write_scope']) && !preg_match('/trp_/i', $sql)) {
        foreach ($allowed as $table) {
            if (preg_match('/^(?:INSERT(?: IGNORE)? INTO|REPLACE INTO|UPDATE|DELETE FROM)\\s+`?' . preg_quote($table, '/') . '`?\\s/i', trim($sql))) {
                $GLOBALS['nc5_write_counts'][$table] = ($GLOBALS['nc5_write_counts'][$table] ?? 0) + 1;
                return $sql;
            }
        }
        throw new RuntimeException('Unexpected database mutation outside local setup tables.');
    }
    $GLOBALS['nc5_suppressed'] = ($GLOBALS['nc5_suppressed'] ?? 0) + 1;
    return 'SELECT NULL WHERE 1 = 0';
});
require dirname(__DIR__, 3) . '/wp-load.php';
if (!in_array(DB_HOST, array('localhost', '127.0.0.1', '::1'), true) || realpath(ABSPATH) !== realpath('C:/xampp/htdocs')) {
    throw new RuntimeException('This routine is restricted to the reviewed local XAMPP installation.');
}
if (!function_exists('acf_get_field_groups') || !post_type_exists('dich-vu') || !taxonomy_exists('danh-muc-dich-vu')) {
    throw new RuntimeException('The existing ACF plugin and its content registrations are required.');
}
