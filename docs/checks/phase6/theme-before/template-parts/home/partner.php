<?php defined('ABSPATH') || exit;
$partner_link = function_exists('get_field') ? get_field('nc_partner_link') : null;
$partner_configured = metadata_exists('post', get_queried_object_id(), 'nc_partner_link');
$partner_url = is_array($partner_link) ? esc_url($partner_link['url'] ?? '') : ($partner_configured ? '' : 'https://gm-group.vn/');
$partner_blank = is_array($partner_link) && ($partner_link['target'] ?? '') === '_blank';
?>
<section class="partner section-border" id="partner" aria-labelledby="partner-title"><div class="container partner-inner">
      <div data-reveal><p class="eyebrow"><?php echo esc_html(nextcore_field('nc_partner_eyebrow', 'Đối tác chiến lược')); ?></p><h2 id="partner-title"><?php echo esc_html(nextcore_field('nc_partner_name', 'GM Solutions')); ?></h2><p><?php echo nl2br(esc_html(nextcore_field('nc_partner_description', "Cùng nhau kiến tạo\nnhững giá trị bền vững."))); ?></p><?php if ($partner_url) : ?><a class="button" href="<?php echo esc_url($partner_url); ?>"<?php if ($partner_blank) : ?> target="_blank" rel="noopener noreferrer"<?php endif; ?>><?php echo esc_html(nextcore_field('nc_partner_link_label', 'Tìm hiểu thêm')); ?> <span aria-hidden="true">→</span></a><?php endif; ?></div><?php nextcore_image(nextcore_field('nc_partner_logo', 'images/gm-solutions.png'), 'GM Solutions', 'partner-logo', 800, 627); ?><p class="partner-motto"><?php echo nl2br(esc_html(nextcore_field('nc_partner_motto', "Stronger\n together\n for a brighter\n tomorrow"))); ?></p>
    </div></section>
