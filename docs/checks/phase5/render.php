<?php
/**
 * CLI-only, request-local theme rendering against core WordPress.
 * Never activate a theme. Block all database mutations before WP bootstrap.
 * Third-party plugins are disabled for this isolated baseline.
 */
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
define('WP_USE_THEMES', false);
define('DISABLE_WP_CRON', true);
define('NEXTCORE_DEVELOPMENT_PREVIEW', true);
$_SERVER['HTTP_HOST'] = 'localhost';
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['REQUEST_URI'] = in_array('--english', $argv, true) ? '/en/' : '/';
$_SERVER['SERVER_PROTOCOL'] = 'HTTP/1.1';
function nextcore_qa_hook($name, $callback, $args = 1) {
    $GLOBALS['wp_filter'][$name][1][] = array('function' => $callback, 'accepted_args' => $args);
}
nextcore_qa_hook('pre_option_template', function () { return 'nextcore-theme'; });
nextcore_qa_hook('pre_option_stylesheet', function () { return 'nextcore-theme'; });
nextcore_qa_hook('pre_option_home', function () { return 'http://localhost'; });
nextcore_qa_hook('pre_option_siteurl', function () { return 'http://localhost'; });
nextcore_qa_hook('pre_option_active_plugins', function () {
    return in_array('--plugins', $GLOBALS['argv'], true) ? array('advanced-custom-fields-pro/acf.php', 'translatepress-multilingual/index.php') : array();
});
nextcore_qa_hook('pre_site_option_active_sitewide_plugins', function () { return array(); });
nextcore_qa_hook('query', function ($sql) {
    if (!preg_match('/^\s*(SELECT|SHOW|DESCRIBE|EXPLAIN)\b/i', $sql)) {
        $GLOBALS['nextcore_qa_blocked_writes'] = ($GLOBALS['nextcore_qa_blocked_writes'] ?? 0) + 1;
        return 'SELECT NULL WHERE 1 = 0';
    }
    return $sql;
});
ob_start();
$capture_level = ob_get_level();
require dirname(__DIR__, 3) . '/wp-load.php';
register_post_type('dich-vu', array('public' => true));
register_taxonomy('danh-muc-dich-vu', array('post', 'dich-vu'), array('public' => true));
wp();
// Phase 5 uses assigned database menus; no RAM menu fixture.
require get_theme_file_path('/front-page.php');
while (ob_get_level() > $capture_level) { ob_end_flush(); }
$html = ob_get_clean();
// Translation has already run server-side. Static review artifacts must not run
// plugin AJAX/telemetry code when opened outside the guarded browser session.
$html = preg_replace_callback('~<script\b([^>]*)>[\s\S]*?</script>~i', function ($match) {
    return preg_match('~\bid=["\x27]nextcore-~i', $match[1]) ? $match[0] : '';
}, $html);
$suffix = in_array('--plugins', $argv, true) ? '-plugins' : '';
$suffix .= in_array('--english', $argv, true) ? '-en' : '';

$destination = __DIR__ . '/rendered' . $suffix . '.html';
file_put_contents($destination, $html);
echo "Rendered using WordPress core; suppressed writes: " . ($GLOBALS['nextcore_qa_blocked_writes'] ?? 0) . "\n";
echo "Stored active stylesheet: " . $wpdb->get_var("SELECT option_value FROM {$wpdb->options} WHERE option_name = 'stylesheet'") . "\n";
