<?php
require __DIR__ . '/bootstrap.php';
$checks = array(); $field_count = 0;
$front = (int) get_option('page_on_front');
$groups = array_values(array_filter(acf_get_field_groups(), function ($g) { return strpos($g['key'], 'group_nc_') === 0; }));
$checks['15_json_groups'] = count($groups) === 15;
$values = array(); $admin = '';
foreach ($groups as $group) {
    $fields = acf_get_fields($group);
    $context = $group['location'][0][0]['param'] === 'options_page' ? 'option' : $front;
    foreach ($fields as $f) {
        $field_count += 1 + count($f['sub_fields'] ?? array());
        $values[$f['name']] = get_field($f['key'], $context);
        $reference = $context === 'option' ? get_option('_options_' . $f['name']) : get_post_meta($front, '_' . $f['name'], true);
        $checks['reference_' . $f['name']] = $reference === $f['key'];
    }
    ob_start();
    acf_render_fields($fields, $context, 'div', 'label');
    $admin .= '<h2>' . esc_html($group['title']) . '</h2>' . ob_get_clean();
}
$checks['105_fields'] = $field_count === 105;
$checks['founded_date_format'] = $values['nc_about_founded_date'] === '2022-06-15';
$checks['full_quotes'] = array_column($values['nc_testimonials'], 'quote') === array_column(nextcore_home_defaults()['nc_testimonials'], 'quote');
$checks['portal_empty'] = !$values['nc_featured_projects'][0]['object'];
$checks['integration_disabled'] = nextcore_integration_ownership()['enabled'] === false;
$checks['taxonomy_no_save'] = acf_get_field('field_nc_service_cards_target_term')['save_terms'] === 0;
$test = function ($name, $value) { $f = acf_get_field('field_' . $name); return nextcore_acf_validate(true, $value, $f, $f['key']); };
$invalid = $values['nc_service_cards']; $invalid[0]['target_term'] = $invalid[1]['target_term'];
$checks['reject_dual_service_branch'] = $test('nc_service_cards', $invalid) !== true;
$invalid = $values['nc_featured_projects']; $invalid[0]['object'] = 1809;
$checks['reject_draft_project'] = $test('nc_featured_projects', $invalid) !== true;
$invalid = $values['nc_featured_projects']; $invalid[2]['object'] = $invalid[1]['object'];
$checks['reject_duplicate_project'] = $test('nc_featured_projects', $invalid) !== true;
$checks['allow_pending_portal'] = $test('nc_featured_projects', $values['nc_featured_projects']) === true;
$checks['reject_bad_phone'] = $test('nc_contact_phone', '123') !== true;
$checks['reject_script'] = $test('nc_about_body', '<script>alert(1)</script>') !== true;
$checks['clean_editor_html'] = nextcore_acf_about_html('<p>Text <strong>bold</strong><img src=x><script>alert(1)</script></p>') === '<p>Text <strong>bold</strong>alert(1)</p>';
$checks['valid_phone'] = $test('nc_contact_phone', '+84378962625') === true;
$checks['empty_optional_partner_link'] = $test('nc_partner_link', array('url' => '', 'title' => '', 'target' => '')) === true;
$checks['reject_unsafe_partner_link'] = $test('nc_partner_link', array('url' => 'javascript:alert(1)')) !== true;
// Verify consumers with request-only ACF overrides; never write test values.
wp();
$empty_social = function () { return false; };
add_filter('acf/format_value/name=nc_social_links', $empty_social, 30);
acf_flush_value_cache('options', 'nc_social_links');
$checks['empty_social_does_not_restore_fallback'] = nextcore_rows('nc_social_links', 'option') === array();
remove_filter('acf/format_value/name=nc_social_links', $empty_social, 30);
acf_flush_value_cache('options', 'nc_social_links');
$custom_technology = function ($rows) { $rows[0]['label'] = 'QA technology label'; return $rows; };
add_filter('acf/format_value/name=nc_technology_items', $custom_technology, 30);
acf_flush_value_cache($front, 'nc_technology_items');
ob_start(); get_template_part('template-parts/home/technology'); $technology_html = ob_get_clean();
$checks['technology_label_is_editable'] = strpos($technology_html, '>QA technology label</li>') !== false;
remove_filter('acf/format_value/name=nc_technology_items', $custom_technology, 30);
acf_flush_value_cache($front, 'nc_technology_items');
$menus = array();
foreach (get_theme_mod('nav_menu_locations', array()) as $location => $id) {
    $items = wp_get_nav_menu_items($id);
    $menus[$location] = array_map(function ($item) { return array('id' => $item->ID, 'parent' => $item->menu_item_parent, 'title' => $item->title, 'url' => $item->url, 'type' => $item->type, 'object_id' => $item->object_id); }, $items);
    $checks[$location . '_count'] = count($items) === ($location === 'home_mobile' ? 15 : 13);
}
$posts = new WP_Query(array('post_type' => 'post', 'post_status' => 'publish', 'posts_per_page' => 3, 'orderby' => array('date' => 'DESC', 'ID' => 'DESC'), 'ignore_sticky_posts' => true, 'no_found_rows' => true));
$blog = array_map(function ($p) { return array('id' => $p->ID, 'title' => $p->post_title, 'date' => $p->post_date, 'url' => get_permalink($p)); }, $posts->posts);
$checks['latest3_blog'] = count($blog) === 3;
$checks['admin_media_controls'] = strpos($admin, 'acf-image-uploader') !== false && strpos($admin, 'acf-file-uploader') !== false;
$checks['admin_relationship_controls'] = strpos($admin, 'data-type="post_object"') !== false && strpos($admin, 'data-type="taxonomy"') !== false;
$report = array('checks' => $checks, 'fields' => $field_count, 'values' => $values, 'menus' => $menus, 'blog' => $blog, 'suppressed_writes' => $GLOBALS['nc5_suppressed'] ?? 0);
file_put_contents(__DIR__ . '/validation-results.json', wp_json_encode($report, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
// Static markup only: no nonce, scripts or actionable form exported.
$admin = preg_replace('~<script\\b[^>]*>[\\s\\S]*?</script>~i', '', $admin);
$admin = preg_replace('~\\sdata-nonce="[^"]*"~i', '', $admin);
$admin = preg_replace_callback('~<input\\b[^>]*>~i', function ($match) {
    return stripos($match[0], 'nonce') !== false ? '' : $match[0];
}, $admin);
file_put_contents(__DIR__ . '/admin-fields.html', '<!doctype html><meta charset="utf-8"><title>ACF field markup review — read only</title>' . $admin);
echo wp_json_encode(array('checks' => count($checks), 'failed' => array_keys(array_filter($checks, function ($v) { return !$v; }))), JSON_PRETTY_PRINT);
