<?php defined('ABSPATH') || exit; ?>
<section class="hero hero--platform" id="home" aria-labelledby="hero-title">
      <?php nextcore_mode_image('hero', 'hero-image', __('Nền tảng phần mềm kết nối đám mây, dữ liệu, phát triển và bảo mật', 'nextcore-theme'), 1672, 941); ?>
      <div class="container hero-inner"><div class="hero-copy" data-reveal>
        <p class="eyebrow"><?php echo esc_html(nextcore_field('nc_hero_eyebrow', __('Software development company', 'nextcore-theme'))); ?></p>
        <h1 id="hero-title" class="company-title translation-block"><span class="company-line"><?php echo esc_html(nextcore_field('nc_hero_line_one', __('Công ty cổ phần Phần', 'nextcore-theme'))); ?></span> <span class="company-line"><?php echo esc_html(nextcore_field('nc_hero_line_two_prefix', __('mềm', 'nextcore-theme'))); ?> <span class="company-name"><?php echo esc_html(nextcore_field('nc_hero_brand', __('Nextcore', 'nextcore-theme'))); ?></span></span></h1>
        <p class="hero-slogan"><?php echo esc_html(nextcore_field('nc_hero_slogan', __('BUILD TRUST, CREATE VALUE', 'nextcore-theme'))); ?></p>
        <p class="hero-description"><?php echo esc_html(nextcore_field('nc_hero_description', __('Giải pháp phần mềm cho doanh nghiệp hiện đại.', 'nextcore-theme'))); ?></p>
        <div class="button-row"><?php if (nextcore_contact_url()) : ?><a class="button" href="<?php echo esc_url(nextcore_contact_url()); ?>"><?php echo esc_html(nextcore_field('nc_hero_contact_label', __('Liên hệ ngay', 'nextcore-theme'))); ?> <span aria-hidden="true">→</span></a><?php endif; ?><a class="button-text" href="#services"><span class="circle" aria-hidden="true">↗</span> <?php echo esc_html(nextcore_field('nc_hero_secondary_label', __('Xem năng lực', 'nextcore-theme'))); ?></a></div>
      </div></div>
    </section>
