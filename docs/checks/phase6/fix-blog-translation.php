<?php
/** Match the approved EN-087 translation to TranslatePress's HTML-encoded source. */
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
define('DISABLE_WP_CRON', true);
$_SERVER['HTTP_HOST'] = 'localhost';
$_SERVER['REQUEST_URI'] = '/';
$_SERVER['REQUEST_METHOD'] = 'GET';
$GLOBALS['wp_filter']['query'][1][] = array('function' => function ($sql) {
    if (preg_match('/^\s*(SELECT|SHOW|DESCRIBE|EXPLAIN)\b/i', $sql)) { return $sql; }
    if (!empty($GLOBALS['nc6_blog_write']) && preg_match('/^UPDATE\s+`?[a-z0-9_]+trp_dictionary_vi_en_us`?\s/i', $sql)) { return $sql; }
    return 'SELECT NULL WHERE 1=0';
}, 'accepted_args' => 1);
ob_start();
require dirname(__DIR__, 3) . '/wp-load.php';
if (!in_array(DB_HOST, array('localhost', '127.0.0.1', '::1'), true) || get_stylesheet() !== 'nextcore-theme') { throw new RuntimeException('Local Nextcore required.'); }
$approved = json_decode(file_get_contents(__DIR__ . '/approved-translations.json'), true);
$entry = array_values(array_filter($approved, function ($item) { return $item['id'] === 87; }))[0];
$source = esc_html($entry['source']);
$translation = esc_html($entry['translation']);
$table = TRP_Translate_Press::get_trp_instance()->get_component('query')->get_table_name('en_US');
$rows = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table WHERE BINARY original=%s", $source), ARRAY_A);
if (count($rows) !== 1) { throw new RuntimeException('Expected one matching encoded source.'); }
$row = $rows[0];
if (!in_array($row['translated'], array('', null, $translation), true)) { throw new RuntimeException('Translation conflict; no write.'); }
$apply = in_array('--apply-local', $argv, true);
$report = array('review_id' => 87, 'mode' => $apply ? 'apply-local' : 'dry-run', 'source' => $source, 'old_translation' => $row['translated'], 'new_translation' => $translation, 'table' => $table, 'type' => 'regular', 'row_id' => $row['id'], 'action' => 'match-encoded-source', 'before_count' => (int) $wpdb->get_var("SELECT COUNT(*) FROM $table"));
$path = __DIR__ . '/translation-blog-' . ($apply ? 'apply-' . gmdate('Ymd-His') : 'dry-run') . '.json';
if (file_put_contents($path, wp_json_encode($report, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) === false) { throw new RuntimeException('Cannot write journal.'); }
if ($apply && $row['translated'] !== $translation) {
    $GLOBALS['nc6_blog_write'] = true;
    $result = $wpdb->update($table, array('translated' => $translation, 'status' => 2), array('id' => $row['id'], 'original' => $source));
    $GLOBALS['nc6_blog_write'] = false;
    if ($result !== 1) { throw new RuntimeException('Translation write failed.'); }
}
$report['after_count'] = (int) $wpdb->get_var("SELECT COUNT(*) FROM $table");
$report['verified_translation'] = $wpdb->get_var($wpdb->prepare("SELECT translated FROM $table WHERE id=%d", $row['id']));
file_put_contents($path, wp_json_encode($report, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
while (ob_get_level()) { ob_end_clean(); }
echo wp_json_encode($report, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
