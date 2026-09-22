<?php
defined('ABSPATH') || exit;

$testimonials_intro_default = __('Những chia sẻ thực tế từ khách hàng đang đồng hành và phát triển cùng Nextcore.', 'nextcore-theme');
$testimonial_slogan = __((string) nextcore_field('nc_testimonials_slogan', 'Chúng tôi không chỉ tạo ra sản phẩm, mà còn xây dựng những mối quan hệ bền vững.'), 'nextcore-theme');
$testimonial_slogan_author = __((string) nextcore_field('nc_testimonials_slogan_author', 'Đội ngũ Nextcore'), 'nextcore-theme');
?>
<section class="testimonials section" id="testimonials" aria-labelledby="testimonials-title">
      <div class="container">
        <div class="testimonial-heading" data-reveal>
          <p class="eyebrow"><?php echo esc_html(nextcore_field('nc_testimonials_eyebrow', __('KHÁCH HÀNG NÓI VỀ NEXTCORE', 'nextcore-theme'))); ?></p>
          <h2 id="testimonials-title"><?php echo esc_html(nextcore_field('nc_testimonials_heading', __('Đánh giá từ khách hàng', 'nextcore-theme'))); ?></h2>
          <p class="testimonial-intro"><?php echo esc_html(nextcore_field('nc_testimonials_intro', $testimonials_intro_default)); ?></p>
          <?php if ($testimonial_slogan) : ?>
            <blockquote class="testimonial-slogan">
              <p><?php echo esc_html($testimonial_slogan); ?></p>
              <?php if ($testimonial_slogan_author) : ?><cite>— <?php echo esc_html($testimonial_slogan_author); ?></cite><?php endif; ?>
            </blockquote>
          <?php endif; ?>
        </div>
        <div class="testimonial-carousel" role="region" aria-roledescription="<?php esc_attr_e('Băng chuyền', 'nextcore-theme'); ?>" aria-label="<?php esc_attr_e('Đánh giá từ khách hàng', 'nextcore-theme'); ?>">
          <button class="testimonial-prev" type="button" aria-label="<?php esc_attr_e('Đánh giá trước', 'nextcore-theme'); ?>">←</button>
          <div class="testimonial-track" tabindex="0" aria-label="<?php esc_attr_e('Vuốt để xem các đánh giá', 'nextcore-theme'); ?>"><?php foreach (nextcore_rows('nc_testimonials') as $row) :
              $quote = trim((string) ($row['quote'] ?? ''));
              $quote_excerpt = wp_trim_words($quote, 28, '...');
          ?>
<article class="testimonial-card">
    <div class="testimonial-person"><?php nextcore_image($row['image'] ?? 0, $row['name'] ?? '', 'notranslate', 64, 64); ?><div><h3 class="notranslate" translate="no" data-nextcore-no-translate><?php echo esc_html($row['name'] ?? ''); ?></h3><p class="notranslate" translate="no" data-nextcore-no-translate><?php echo esc_html($row['project_label'] ?? ''); ?></p></div></div>
    <blockquote class="notranslate" translate="no" data-nextcore-no-translate><?php echo esc_html($quote_excerpt); ?></blockquote>
    <div class="testimonial-full-quote notranslate" translate="no" data-nextcore-no-translate hidden><?php echo esc_html($quote); ?></div>
    <button class="testimonial-more" type="button"><?php esc_html_e('Xem chi tiết', 'nextcore-theme'); ?><span aria-hidden="true">→</span><span class="sr-only"> — <?php echo esc_html($row['name'] ?? ''); ?></span></button>
</article>
<?php endforeach; ?></div>
          <button class="testimonial-next" type="button" aria-label="<?php esc_attr_e('Đánh giá tiếp theo', 'nextcore-theme'); ?>">→</button>
        </div>
        <div class="testimonial-dots" aria-label="<?php esc_attr_e('Chọn nhóm đánh giá', 'nextcore-theme'); ?>"></div>
      </div>
    </section>

<dialog class="testimonial-dialog nextcore-native" aria-labelledby="testimonial-dialog-title">
  <button class="testimonial-close" type="button" aria-label="<?php esc_attr_e('Đóng đánh giá', 'nextcore-theme'); ?>">×</button>
  <div class="testimonial-dialog-person">
    <img class="testimonial-dialog-avatar" alt="" width="72" height="72" hidden>
    <div>
      <h2 class="notranslate" id="testimonial-dialog-title" translate="no" data-nextcore-no-translate></h2>
      <p class="testimonial-dialog-project notranslate" translate="no" data-nextcore-no-translate></p>
    </div>
  </div>
  <blockquote class="notranslate" translate="no" data-nextcore-no-translate></blockquote>
</dialog>


