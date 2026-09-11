<?php
require __DIR__ . '/bootstrap.php';
$groups = array_values(array_filter(acf_get_field_groups(), function ($g) { return strpos($g['key'], 'group_nc_') === 0; }));
$terms = get_terms(array('taxonomy' => array('category', 'danh-muc-dich-vu'), 'hide_empty' => false));
$data = array(
    'groups' => array_column($groups, 'key'),
    'frontpage' => get_option('page_on_front'),
    'stored_stylesheet' => $wpdb->get_var("SELECT option_value FROM {$wpdb->options} WHERE option_name='stylesheet'"),
    'terms' => array_map(function ($t) { return array('id' => $t->term_id, 'slug' => $t->slug, 'taxonomy' => $t->taxonomy); }, $terms),
    'theme_mods' => get_option('theme_mods_nextcore-theme'),
    'upload_url_path' => get_option('upload_url_path'),
    'dictionary_tables' => $wpdb->get_col("SHOW TABLES LIKE '%trp%'"),
    'posts' => $wpdb->get_results("SELECT ID,post_type,post_status,post_name,post_title FROM {$wpdb->posts} WHERE post_name IN ('outsource','lien-he','truong-doanh-nhan-top-olympia','wordpress-plugin-affiliate','phan-mem-cham-cong-nextcore-portal') OR ID=1809", ARRAY_A),
);
echo wp_json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
