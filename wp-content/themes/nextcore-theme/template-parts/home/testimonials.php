<?php defined('ABSPATH') || exit; ?>
<section class="testimonials section" id="testimonials" aria-labelledby="testimonials-title">
      <div class="container">
        <div class="testimonial-heading" data-reveal><p class="eyebrow"><?php echo esc_html(nextcore_field('nc_testimonials_eyebrow', 'KHÁCH HÀNG NÓI VỀ NEXTCORE')); ?></p><h2 id="testimonials-title"><?php echo esc_html(nextcore_field('nc_testimonials_heading', 'Đánh giá từ khách hàng')); ?></h2><p><?php echo esc_html(nextcore_field('nc_testimonials_intro', 'Những chia sẻ thực tế từ khách hàng đang đồng hành cùng Nextcore.')); ?></p></div>
        <div class="testimonial-carousel" role="region" aria-roledescription="<?php esc_attr_e('Băng chuyền', 'nextcore-theme'); ?>" aria-label="<?php esc_attr_e('Đánh giá từ khách hàng', 'nextcore-theme'); ?>">
          <button class="testimonial-prev" type="button" aria-label="<?php esc_attr_e('Đánh giá trước', 'nextcore-theme'); ?>">←</button>
          <div class="testimonial-track" tabindex="0" aria-label="<?php esc_attr_e('Vuốt để xem các đánh giá', 'nextcore-theme'); ?>"><?php foreach (nextcore_rows('nc_testimonials') as $row) : ?>
<article class="testimonial-card">
    <div class="testimonial-person"><?php nextcore_image($row['image'] ?? 0, $row['name'] ?? '', '', 64, 64); ?><div><h3><?php echo esc_html($row['name'] ?? ''); ?></h3><p><?php echo esc_html($row['project_label'] ?? ''); ?></p></div></div>
    <blockquote><?php echo esc_html($row['quote'] ?? ''); ?></blockquote>
    <button class="testimonial-more" type="button"><?php esc_html_e('Xem thêm', 'nextcore-theme'); ?><span class="sr-only"> — <?php echo esc_html($row['name'] ?? ''); ?></span></button>
</article>
<?php endforeach; ?></div>
          <button class="testimonial-next" type="button" aria-label="<?php esc_attr_e('Đánh giá tiếp theo', 'nextcore-theme'); ?>">→</button>
        </div>
        <div class="testimonial-dots" aria-label="<?php esc_attr_e('Chọn nhóm đánh giá', 'nextcore-theme'); ?>"></div>
      </div>
    </section>

<dialog class="testimonial-dialog nextcore-native" aria-labelledby="testimonial-dialog-title"><button class="testimonial-close" type="button" aria-label="<?php esc_attr_e('Đóng đánh giá', 'nextcore-theme'); ?>">×</button><h2 id="testimonial-dialog-title"></h2><p class="testimonial-dialog-project"></p><blockquote></blockquote></dialog>


