<?php
defined('ABSPATH') || exit;

$partner_title_default = __('Đối tác chiến lược', 'nextcore-theme');
$partner_intro_default = __('Kết nối cùng các đối tác chiến lược để mở rộng năng lực, chia sẻ thế mạnh và kiến tạo những bước tiến dài hạn.', 'nextcore-theme');
$partner_name = (string) nextcore_field('nc_partner_name', 'GM Solutions');

$partner_link = function_exists('get_field') ? get_field('nc_partner_link') : null;
$partner_configured = metadata_exists('post', get_queried_object_id(), 'nc_partner_link');
$partner_url = is_array($partner_link) ? esc_url($partner_link['url'] ?? '') : ($partner_configured ? '' : 'https://gm-group.vn/');
$partner_blank = is_array($partner_link) && ($partner_link['target'] ?? '') === '_blank';
?>
<section class="partner section-border" id="partner" aria-labelledby="partner-title">
  <div class="container partner-inner">
    <div class="partner-heading" data-reveal>
      <h2 id="partner-title"><?php echo esc_html(nextcore_field('nc_partner_eyebrow', $partner_title_default)); ?></h2>
      <p class="partner-intro"><?php echo esc_html(nextcore_field('nc_partner_intro', $partner_intro_default)); ?></p>
    </div>
    <div class="partner-list" role="list" data-reveal>
      <?php if ($partner_url) : ?>
        <a class="partner-item" role="listitem" href="<?php echo esc_url($partner_url); ?>" aria-label="<?php echo esc_attr($partner_name); ?>"<?php if ($partner_blank) : ?> target="_blank" rel="noopener noreferrer"<?php endif; ?>>
          <?php nextcore_image(nextcore_field('nc_partner_logo', 'images/gm-solutions.png'), $partner_name, 'partner-logo', 800, 627); ?>
        </a>
      <?php else : ?>
        <div class="partner-item" role="listitem"><?php nextcore_image(nextcore_field('nc_partner_logo', 'images/gm-solutions.png'), $partner_name, 'partner-logo', 800, 627); ?></div>
      <?php endif; ?>
    </div>
  </div>
</section>
