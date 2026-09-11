<?php
/** Four Blog card translations explicitly approved in the Phase 6 continuation. */
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
define('DISABLE_WP_CRON', true);
$_SERVER['HTTP_HOST'] = 'localhost'; $_SERVER['REQUEST_URI'] = '/'; $_SERVER['REQUEST_METHOD'] = 'GET';
$GLOBALS['wp_filter']['query'][1][] = array('function' => function ($sql) {
    if (preg_match('/^\s*(SELECT|SHOW|DESCRIBE|EXPLAIN)\b/i', $sql)) { return $sql; }
    if (!empty($GLOBALS['nc6_blog_write']) && preg_match('/^UPDATE\s+`?[a-z0-9_]+trp_dictionary_vi_en_us`?\s/i', $sql)) { return $sql; }
    return 'SELECT NULL WHERE 1=0';
}, 'accepted_args' => 1);
ob_start(); require dirname(__DIR__, 3) . '/wp-load.php';
if (!in_array(DB_HOST, array('localhost', '127.0.0.1', '::1'), true) || get_stylesheet() !== 'nextcore-theme') { throw new RuntimeException('Local Nextcore required.'); }
$html = file_get_contents('http://localhost/');
if (!preg_match('~<section[^>]+id="blog"[^>]*>(.*?)</section>~s', $html, $section)) { throw new RuntimeException('Blog missing.'); }
preg_match_all('~<h3><a[^>]*>(.*?)</a></h3>~s', $section[1], $titles);
preg_match_all('~<p class="blog-excerpt">(.*?)</p>~s', $section[1], $excerpts);
if (count($titles[1]) !== 3 || count($excerpts[1]) !== 3) { throw new RuntimeException('Unexpected Blog cards.'); }
$items = array(
    array('BLOG-01', $titles[1][0], 'Hướng dẫn cách “Debug SpringBoot với Visual code” một cách dễ dàng', 'A simple guide to debugging Spring Boot in Visual Studio Code'),
    array('BLOG-02', $excerpts[1][0], 'Debugging là một kỹ năng quan trọng đối với bất kỳ lập trình viên nào, đặc biệt khi làm việc với các ứng dụng phức tạp…', 'Debugging is an essential skill for any developer, especially when working with complex applications…'),
    array('BLOG-03', $excerpts[1][1], 'Affiliate marketing là một hình thức tiếp thị trong đó một doanh nghiệp thưởng cho một hoặc nhiều người (được gọi là "đối tác liên kết"…', 'Affiliate marketing is a form of marketing in which a business rewards one or more people, known as affiliates…'),
    array('BLOG-04', $excerpts[1][2], 'Trong lĩnh vực giáo dục, việc cung cấp một trang web chất lượng và thân thiện với người dùng là điều vô cùng quan trọng. Trang…', 'In education, providing a high-quality, user-friendly website is essential. The website…'),
);
$table = TRP_Translate_Press::get_trp_instance()->get_component('query')->get_table_name('en_US');
$report = array('mode' => 'apply-local', 'approval' => 'User approved four proposals in docs/phase6-blog-en-supplement-review.md', 'before_count' => (int) $wpdb->get_var("SELECT COUNT(*) FROM $table"), 'writes' => array(), 'conflicts' => array());
foreach ($items as $item) {
    list($id, $source, $expected, $translation) = $item;
    $source = trp_full_trim($source);
    if (html_entity_decode($source, ENT_QUOTES | ENT_HTML5, 'UTF-8') !== $expected) { throw new RuntimeException('Source changed: ' . $id); }
    $rows = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table WHERE BINARY original=%s", $source), ARRAY_A);
    if (count($rows) !== 1) { throw new RuntimeException('Expected one discovered dictionary row: ' . $id); }
    $row = $rows[0];
    if (!in_array($row['translated'], array('', null, $translation), true)) { throw new RuntimeException('Translation conflict: ' . $id); }
    $report['writes'][] = array('review_id' => $id, 'source' => $source, 'old_translation' => $row['translated'], 'new_translation' => $translation, 'table' => $table, 'type' => 'regular', 'row_id' => $row['id'], 'action' => 'apply-approved-blog-supplement');
}
$path = __DIR__ . '/translation-blog-supplement-' . gmdate('Ymd-His') . '.json';
if (file_put_contents($path, wp_json_encode($report, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) === false) { throw new RuntimeException('Cannot write journal.'); }
foreach ($report['writes'] as &$entry) {
    if ($entry['old_translation'] !== $entry['new_translation']) {
        $GLOBALS['nc6_blog_write'] = true;
        $result = $wpdb->update($table, array('translated' => $entry['new_translation'], 'status' => 2), array('id' => $entry['row_id'], 'original' => $entry['source']));
        $GLOBALS['nc6_blog_write'] = false;
        if ($result !== 1) { throw new RuntimeException('Write failed: ' . $entry['review_id']); }
    }
    $entry['verified'] = $wpdb->get_var($wpdb->prepare("SELECT translated FROM $table WHERE id=%d", $entry['row_id'])) === $entry['new_translation'];
    file_put_contents($path, wp_json_encode($report, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}
unset($entry);
$report['after_count'] = (int) $wpdb->get_var("SELECT COUNT(*) FROM $table");
file_put_contents($path, wp_json_encode($report, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
while (ob_get_level()) { ob_end_clean(); }
echo wp_json_encode($report, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
