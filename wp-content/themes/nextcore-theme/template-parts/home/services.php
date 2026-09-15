<?php defined('ABSPATH') || exit; ?>
<section class="section container" id="services" aria-labelledby="services-title">
      <div class="section-heading" data-reveal><div><p class="eyebrow"><?php echo esc_html(nextcore_field('nc_services_eyebrow', 'Dịch vụ')); ?></p><h2 id="services-title"><?php echo esc_html(nextcore_field('nc_services_heading', 'Năng lực cốt lõi')); ?></h2></div><?php if (nextcore_contact_url()) : ?><a class="text-link" href="<?php echo esc_url(nextcore_contact_url()); ?>"><?php echo esc_html(nextcore_field('nc_services_contact_label', 'Trao đổi nhu cầu ')); ?><span aria-hidden="true">→</span></a><?php endif; ?></div>
      <div class="services-grid">
        <?php foreach (nextcore_rows('nc_service_cards') as $row) :
    $url = nextcore_service_url($row);
    $tag = $url ? 'a' : 'article';
    $title = (string) ($row['title'] ?? '');
    $featured = array('Dịch vụ nổi bật');
    if (stripos($title, 'Outsource') !== false) {
        $featured = array('Web/App', 'Bảo trì', 'Outsource team');
    } elseif (stripos($title, 'Tư vấn') !== false || stripos($title, 'Tu van') !== false) {
        $featured = array('Tư vấn', 'Chiến lược', 'Tối ưu vận hành');
    } elseif (stripos($title, 'cá nhân') !== false || stripos($title, 'ca nhan') !== false) {
        $featured = array('Website', 'Tự động hóa', 'Linh hoạt');
    }
?>
<<?php echo $tag; ?> class="service-card" <?php if ($url) : ?>href="<?php echo esc_url($url); ?>"<?php endif; ?> data-reveal>
    <?php nextcore_image($row['image'] ?? 0, '', 'service-image', 1024, 1280); ?>
    <span class="service-icon"><svg class="line-icon" aria-hidden="true"><use href="#i-<?php echo esc_attr(in_array($row['icon'] ?? '', array('code', 'strategy', 'user'), true) ? $row['icon'] : 'code'); ?>"/></svg></span>
    <h3><?php echo esc_html($title); ?></h3>
    <p><?php echo esc_html($row['description'] ?? ''); ?></p>
    <div class="service-bottom">
        <span class="service-cta">Tìm hiểu thêm <span aria-hidden="true">→</span></span>
        <span class="service-featured" aria-hidden="true">
            <span class="service-featured-title">Dịch vụ nổi bật</span>
            <span class="service-tags"><?php foreach ($featured as $tag_label) : ?><span><?php echo esc_html($tag_label); ?></span><?php endforeach; ?></span>
        </span>
    </div>
</<?php echo $tag; ?>>
<?php endforeach; ?>
</div>
    </section>
