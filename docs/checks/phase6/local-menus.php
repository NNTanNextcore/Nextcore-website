<?php
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
define('DISABLE_WP_CRON', true);
$_SERVER['HTTP_HOST'] = 'localhost'; $_SERVER['REQUEST_URI'] = '/'; $_SERVER['REQUEST_METHOD'] = 'GET';
$GLOBALS['wp_filter']['query'][1][] = array('function' => function ($sql) {
    return !empty($GLOBALS['nc6_menu_write']) || preg_match('/^\s*(SELECT|SHOW|DESCRIBE|EXPLAIN)\b/i', $sql) ? $sql : 'SELECT NULL WHERE 1=0';
}, 'accepted_args' => 1);
ob_start(); require dirname(__DIR__, 3) . '/wp-load.php';
if (!in_array(DB_HOST, array('localhost','127.0.0.1','::1'), true) || get_stylesheet() !== 'nextcore-theme') { throw new RuntimeException('Active Nextcore local required.'); }
$locations = get_theme_mod('nav_menu_locations', array());
$report = array('before' => $locations, 'menus' => array());
$GLOBALS['nc6_menu_write'] = true;
try {
    $locations['primary'] = $locations['home_primary'];
    $locations['primary_mobile'] = $locations['home_mobile'];
    foreach (array('footer_about' => array('about'=>'Giới thiệu','team'=>'Đội ngũ','projects'=>'Dự án'), 'footer_support' => array('blog'=>'Tin tức','services'=>'Dịch vụ','contact'=>'Liên hệ')) as $location => $items) {
        if (!empty($locations[$location])) { continue; }
        $name = 'Nextcore ' . $location;
        $menu = wp_get_nav_menu_object($name);
        if ($menu) { throw new RuntimeException('Unexpected existing unassigned menu; review first.'); }
        $id = wp_create_nav_menu($name);
        if (is_wp_error($id)) { throw new RuntimeException($id->get_error_message()); }
        $report['menus'][$location] = array('id'=>$id,'items'=>array());
        foreach ($items as $anchor=>$label) {
            $args = array('menu-item-title'=>$label,'menu-item-type'=>'custom','menu-item-url'=>'#'.$anchor,'menu-item-status'=>'publish');
            $item = wp_update_nav_menu_item($id,0,$args);
            $report['menus'][$location]['items'][] = array('id'=>$item,'args'=>$args);
        }
        $locations[$location] = $id;
    }
    if ($report['before'] !== $locations) { set_theme_mod('nav_menu_locations', $locations); }
} finally { $GLOBALS['nc6_menu_write'] = false; }
$report['after'] = get_theme_mod('nav_menu_locations');
while(ob_get_level()){ob_end_clean();}
$path = __DIR__ . '/menu-write-' . gmdate('Ymd-His') . '.json';
$report['journal'] = basename($path);
file_put_contents($path,wp_json_encode($report,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES));
echo wp_json_encode($report,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
