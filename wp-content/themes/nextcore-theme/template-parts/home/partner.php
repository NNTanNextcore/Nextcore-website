<?php
defined('ABSPATH') || exit;

$partner_headline_default = __('Đối tác chiến lược đồng hành cùng Nextcore', 'nextcore-theme');
$partner_intro_default = __('Kết nối cùng GM Solutions để mở rộng năng lực, chia sẻ thế mạnh và cùng kiến tạo những giải pháp bền vững cho khách hàng doanh nghiệp.', 'nextcore-theme');
$partner_intro_legacy = __('Kết nối cùng các đối tác chiến lược để mở rộng năng lực, chia sẻ thế mạnh và kiến tạo những bước tiến dài hạn.', 'nextcore-theme');
$partner_link_label_default = __('Tìm hiểu đối tác', 'nextcore-theme');
$partner_name = (string) nextcore_field('nc_partner_name', 'GM Solutions');
$partner_headline = __((string) nextcore_field('nc_partner_headline', $partner_headline_default), 'nextcore-theme');
$partner_description = __((string) nextcore_field('nc_partner_description', $partner_intro_default), 'nextcore-theme');
$partner_description = $partner_description === $partner_intro_legacy ? $partner_intro_default : $partner_description;
$partner_link_label = __((string) nextcore_field('nc_partner_link_label', $partner_link_label_default), 'nextcore-theme');

$partner_link = function_exists('get_field') ? get_field('nc_partner_link') : null;
$partner_configured = metadata_exists('post', get_queried_object_id(), 'nc_partner_link');
$partner_url = is_array($partner_link) ? esc_url($partner_link['url'] ?? '') : ($partner_configured ? '' : 'https://gm-group.vn/');
$partner_blank = is_array($partner_link) && ($partner_link['target'] ?? '') === '_blank';
?>
<section class="partner section-border" id="partner" aria-labelledby="partner-title">
  <div class="container partner-inner">
    <div class="partner-content" data-reveal>
      <h2 id="partner-title"><?php echo esc_html($partner_name); ?></h2>
      <p class="partner-headline"><?php echo esc_html($partner_headline); ?></p>
      <p class="partner-intro"><?php echo esc_html($partner_description); ?></p>
      <?php if ($partner_url && $partner_link_label) : ?>
        <a class="partner-link" href="<?php echo esc_url($partner_url); ?>"<?php if ($partner_blank) : ?> target="_blank" rel="noopener noreferrer"<?php endif; ?>>
          <?php echo esc_html($partner_link_label); ?>
          <span aria-hidden="true">→</span>
        </a>
      <?php endif; ?>
    </div>
    <div class="partner-logo-panel" data-reveal>
      <?php if ($partner_url) : ?>
        <a class="partner-logo-wrap" href="<?php echo esc_url($partner_url); ?>" aria-label="<?php echo esc_attr($partner_name); ?>"<?php if ($partner_blank) : ?> target="_blank" rel="noopener noreferrer"<?php endif; ?>>
          <?php nextcore_image(nextcore_field('nc_partner_logo', 'images/gm-solutions.png'), $partner_name, 'partner-logo', 800, 627); ?>
        </a>
      <?php else : ?>
        <div class="partner-logo-wrap"><?php nextcore_image(nextcore_field('nc_partner_logo', 'images/gm-solutions.png'), $partner_name, 'partner-logo', 800, 627); ?></div>
      <?php endif; ?>
    </div>
  </div>
</section>
