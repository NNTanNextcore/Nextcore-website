<?php
/** Isolated read-only process; no plugin activation options are changed. */
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
define('DISABLE_WP_CRON', true);
$_SERVER['HTTP_HOST'] = 'localhost';
$_SERVER['SERVER_NAME'] = 'localhost';
$_SERVER['SERVER_PORT'] = '80';
$_SERVER['REQUEST_URI'] = '/';
$_SERVER['REQUEST_METHOD'] = 'GET';
$GLOBALS['wp_filter']['query'][1][] = array('function' => function ($sql) {
    return preg_match('/^\s*(SELECT|SHOW|DESCRIBE|EXPLAIN)\b/i', $sql) ? $sql : 'SELECT NULL WHERE 1=0';
}, 'accepted_args' => 1);
$GLOBALS['wp_filter']['pre_option_active_plugins'][1][] = array('function' => function () { return array(); }, 'accepted_args' => 1);
ob_start();
require dirname(__DIR__, 3) . '/wp-load.php';
if (!in_array(DB_HOST, array('localhost', '127.0.0.1', '::1'), true) || get_stylesheet() !== 'nextcore-theme') { throw new RuntimeException('Local Nextcore required.'); }
$report = array('isolated_plugins' => wp_get_active_and_valid_plugins(), 'checks' => array());
foreach (array('/' => 'front-page.php', '/danh-muc-dich-vu/tu-van-doanh-nghiep/' => 'taxonomy-danh-muc-dich-vu.php', '/ve-chung-toi/' => 'page.php', '/phase6-optional-404/' => '404.php') as $route => $template) {
    $_SERVER['REQUEST_URI'] = $route;
    $wp->main();
    ob_start();
    try {
        include get_theme_file_path($template);
        $html = ob_get_clean();
        $report['checks'][] = array('route' => $route, 'template' => $template, 'no_fatal' => true, 'bytes' => strlen($html), 'breadcrumb_fallback' => strpos($html, '<ol>') !== false);
    } catch (Throwable $error) {
        ob_end_clean();
        $report['checks'][] = array('route' => $route, 'no_fatal' => false, 'error' => $error->getMessage());
    }
}
while (ob_get_level()) { ob_end_clean(); }
echo wp_json_encode($report, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
