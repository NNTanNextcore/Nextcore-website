<?php
// In-memory QA menu only. WordPress's real wp_nav_menu and Walker render it.
if (PHP_SAPI !== 'cli') { exit; }
add_filter('wp_nav_menu_args', function ($args) {
    if (strpos($args['theme_location'], 'footer_') === 0) { return $args; }
    $args['menu'] = strpos($args['theme_location'], 'mobile') !== false ? -901 : -900;
    return $args;
});
add_filter('wp_get_nav_menu_object', function ($menu, $requested) {
    if (!is_numeric($requested) || !in_array((int) $requested, array(-900, -901), true)) { return $menu; }
    return new WP_Term((object) array('term_id' => (int) $requested, 'term_taxonomy_id' => (int) $requested, 'name' => 'QA menu', 'slug' => 'qa-menu', 'taxonomy' => 'nav_menu', 'count' => 0, 'description' => '', 'parent' => 0, 'term_group' => 0));
}, 10, 2);
add_filter('wp_get_nav_menu_items', function ($items, $menu) {
    if (!in_array($menu->term_id, array(-900, -901), true)) { return $items; }
    $rows = array(
        array(1, 0, 'Giới thiệu', '#about'),
        array(2, 0, 'Dịch vụ', '#services'),
        array(3, 2, 'Outsource', nextcore_page_url('outsource')),
        array(4, 2, 'Tư vấn doanh nghiệp', nextcore_service_url(array('target_kind' => 'term', 'target_slug' => 'tu-van-doanh-nghiep'))),
        array(5, 2, 'Khách hàng cá nhân', nextcore_service_url(array('target_kind' => 'term', 'target_slug' => 'khach-hang-ca-nhan'))),
        array(6, 2, 'Sản phẩm', nextcore_service_url(array('target_kind' => 'term', 'target_slug' => 'san-pham'))),
        array(7, 0, 'Công nghệ', '#technology'),
        array(8, 0, 'Sản phẩm / Dự án', '#projects'),
    );
    if ($menu->term_id === -901) { $rows[] = array(9, 0, 'Đối tác', '#partner'); $rows[] = array(10, 0, 'Đánh giá', '#testimonials'); }
    $rows = array_merge($rows, array(
        array(11, 0, 'Đội ngũ', '#team'),
        array(12, 0, 'Tin tức', '#blog'),
        array(13, 12, 'Kiến thức', home_url('/category/kien-thuc/')),
        array(14, 12, 'Công ty', home_url('/category/cong-ty/')),
        array(15, 0, 'Liên hệ', '#contact'),
    ));
    $result = array();
    foreach ($rows as $index => $row) {
        $result[] = (object) array('ID' => $row[0], 'db_id' => $row[0], 'menu_item_parent' => $row[1], 'title' => $row[2], 'url' => $row[3], 'type' => 'custom', 'object' => 'custom', 'object_id' => $row[0], 'classes' => array(), 'target' => '', 'attr_title' => '', 'description' => '', 'xfn' => '', 'menu_order' => $index + 1);
    }
    return $result;
}, 10, 2);
