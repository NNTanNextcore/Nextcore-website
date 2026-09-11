<?php defined('ABSPATH') || exit; ?>
<nav class="nextcore-breadcrumbs" aria-label="<?php esc_attr_e('Đường dẫn trang', 'nextcore-theme'); ?>">
<?php
$breadcrumbs = function_exists('yoast_breadcrumb') ? yoast_breadcrumb('', '', false) : '';
if ($breadcrumbs) { echo wp_kses_post($breadcrumbs); }
else { nextcore_native_breadcrumbs(); }
?>
</nav>
