<?php
/** One-time correction of ACF's automatic parent side effect, recorded from the first setup journal. */
require __DIR__ . '/bootstrap.php';
if (!in_array('--apply-local', $argv, true)) { echo "Use --apply-local for the journal-verified correction.\n"; exit; }
$journal = json_decode(file_get_contents(__DIR__ . '/local-write-20260911-023252.json'), true);
$changes = array();
foreach ($journal['database_diff'] as $change) {
    if ($change['table'] !== $wpdb->posts || $change['operation'] !== 'updated' || $change['before']['post_type'] !== 'attachment') { continue; }
    $current = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->posts} WHERE ID=%d", $change['id']), ARRAY_A);
    if ($current === $change['before']) { continue; }
    if ($current !== $change['after']) { throw new RuntimeException('Attachment changed since setup; refusing correction.'); }
    $restore = array();
    foreach ($change['before'] as $key => $value) { if ($value !== $current[$key]) { $restore[$key] = $value; } }
    $GLOBALS['nc5_write_scope'] = true;
    try { $wpdb->update($wpdb->posts, $restore, array('ID' => $change['id'])); }
    finally { $GLOBALS['nc5_write_scope'] = false; }
    clean_post_cache($change['id']);
    $changes[] = array('attachment_id' => $change['id'], 'restored_columns' => $restore, 'reason' => 'Preserve existing attachment parent, timestamps and comment status.');
}
file_put_contents(__DIR__ . '/attachment-parent-correction.json', wp_json_encode($changes, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
echo count($changes) . " journal-verified attachment correction(s).\n";
