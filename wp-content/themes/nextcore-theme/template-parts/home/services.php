<?php
defined('ABSPATH') || exit;

$services_intro_default = __('Từ chiến lược đến triển khai, Nextcore đồng hành cùng doanh nghiệp xây dựng các giải pháp công nghệ hiệu quả, linh hoạt và phù hợp với từng giai đoạn phát triển.', 'nextcore-theme');
$services_slogan = __('Thấu hiểu nhu cầu - Kiến tạo giải pháp - Đồng hành phát triển', 'nextcore-theme');
?>
<section class="section container" id="services" aria-labelledby="services-title">
      <div class="section-heading" data-reveal><div><p class="eyebrow"><?php echo esc_html(nextcore_field('nc_services_eyebrow', __('Dịch vụ', 'nextcore-theme'))); ?></p><h2 id="services-title"><?php echo esc_html(nextcore_field('nc_services_heading', __('Năng lực cốt lõi', 'nextcore-theme'))); ?></h2><p class="services-intro"><?php echo esc_html(nextcore_field('nc_services_intro', $services_intro_default)); ?></p></div><?php if (nextcore_contact_url()) : ?><a class="text-link" href="<?php echo esc_url(nextcore_contact_url()); ?>"><?php echo esc_html(nextcore_field('nc_services_contact_label', __('Trao đổi nhu cầu', 'nextcore-theme'))); ?> <span aria-hidden="true">→</span></a><?php endif; ?></div>
      <div class="services-grid">
        <?php foreach (nextcore_rows('nc_service_cards') as $row) :
    $url = nextcore_service_url($row);
    $tag = $url ? 'a' : 'article';
    $title = (string) ($row['title'] ?? '');
    $featured = array(__('Dịch vụ nổi bật', 'nextcore-theme'));
    if (stripos($title, 'Outsource') !== false) {
        $featured = array('Web/App', __('Bảo trì', 'nextcore-theme'), 'Outsource team');
    } elseif (stripos($title, 'Tư vấn') !== false || stripos($title, 'Tu van') !== false) {
        $featured = array(__('Tư vấn', 'nextcore-theme'), __('Chiến lược', 'nextcore-theme'), __('Tối ưu vận hành', 'nextcore-theme'));
    } elseif (stripos($title, 'cá nhân') !== false || stripos($title, 'ca nhan') !== false) {
        $featured = array('Website', __('Tự động hóa', 'nextcore-theme'), __('Linh hoạt', 'nextcore-theme'));
    }
?>
<<?php echo $tag; ?> class="service-card" <?php if ($url) : ?>href="<?php echo esc_url($url); ?>"<?php endif; ?> data-reveal>
    <?php nextcore_image($row['image'] ?? 0, '', 'service-image', 1024, 1280); ?>
    <span class="service-icon"><svg class="line-icon" aria-hidden="true"><use href="#i-<?php echo esc_attr(in_array($row['icon'] ?? '', array('code', 'strategy', 'user'), true) ? $row['icon'] : 'code'); ?>"/></svg></span>
    <h3><?php echo esc_html($title); ?></h3>
    <p><?php echo esc_html($row['description'] ?? ''); ?></p>
    <div class="service-bottom">
        <span class="service-cta"><?php esc_html_e('Tìm hiểu thêm', 'nextcore-theme'); ?> <span aria-hidden="true">→</span></span>
        <span class="service-featured" aria-hidden="true">
            <span class="service-featured-title"><?php esc_html_e('Dịch vụ nổi bật', 'nextcore-theme'); ?></span>
            <span class="service-tags"><?php foreach ($featured as $tag_label) : ?><span><?php echo esc_html($tag_label); ?></span><?php endforeach; ?></span>
        </span>
    </div>
</<?php echo $tag; ?>>
<?php endforeach; ?>
</div>
      <p class="services-slogan" data-reveal><?php echo esc_html($services_slogan); ?></p>
    </section>
