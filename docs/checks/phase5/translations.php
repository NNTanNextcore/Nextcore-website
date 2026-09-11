<?php
require __DIR__ . '/bootstrap.php';
$sources = json_decode(file_get_contents(__DIR__ . '/translation-sources.json'), true);
$tables = array($wpdb->prefix . 'trp_dictionary_vi_en_us', $wpdb->prefix . 'trp_gettext_en_us');
$normalize = function ($text) { return trim(preg_replace('/\\s+/u', ' ', html_entity_decode(strip_tags($text), ENT_QUOTES, 'UTF-8'))); };
$index = array();
foreach ($sources as $i => $source) { $index[$normalize($source['vi'])][] = $i; $sources[$i]['existing'] = array(); }
foreach ($tables as $table) {
    $columns = $wpdb->get_col("SHOW COLUMNS FROM $table");
    if (!in_array('original', $columns, true) || !in_array('translated', $columns, true)) { throw new RuntimeException('Unexpected dictionary schema.'); }
    foreach ($wpdb->get_results("SELECT original,translated,status FROM $table WHERE translated IS NOT NULL AND translated<>''", ARRAY_A) as $row) {
        $key = $normalize($row['original']);
        foreach ($index[$key] ?? array() as $i) {
            $sources[$i]['existing'][] = array('table' => $table, 'original' => $row['original'], 'translated' => $row['translated'], 'status' => $row['status'], 'match' => $row['original'] === $sources[$i]['vi'] ? 'exact' : 'normalized text');
        }
    }
}
file_put_contents(__DIR__ . '/translation-matches.json', wp_json_encode($sources, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
echo count($sources) . " sources checked; dictionary reads only.\n";
