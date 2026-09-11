<?php
defined('ABSPATH') || exit;
$gallery = function_exists('get_field') ? get_field('anh_chi_tiet', get_the_ID()) : array();
if (!is_array($gallery) || !$gallery) { return; }
?>
<div class="nextcore-gallery">
<?php foreach ($gallery as $image) :
    $id = is_array($image) ? absint($image['ID'] ?? $image['id'] ?? 0) : absint($image);
    $url = $id ? wp_get_attachment_image_url($id, 'full') : '';
    if (!$url) { continue; } ?>
    <a href="<?php echo esc_url($url); ?>"><?php echo wp_get_attachment_image($id, 'large', false, array('loading' => 'lazy')); ?></a>
<?php endforeach; ?>
</div>
