<?php defined('ABSPATH') || exit; ?>
<section class="hero" id="home" aria-labelledby="hero-title">
      <?php nextcore_mode_image('hero', 'hero-image', 'Ba đồng nghiệp mặc polo đen có logo N đỏ cùng làm việc bên laptop trong văn phòng hiện đại', 1672, 941); ?>
      <div class="container hero-inner"><div class="hero-copy" data-reveal>
        <p class="eyebrow"><?php echo esc_html(nextcore_field('nc_hero_eyebrow', 'Software development company')); ?></p>
        <h1 id="hero-title" class="company-title"><span class="company-line"><?php echo esc_html(nextcore_field('nc_hero_line_one', 'Công ty cổ phần Phần')); ?></span> <span class="company-line"><?php echo esc_html(nextcore_field('nc_hero_line_two_prefix', 'mềm')); ?> <span class="company-name"><?php echo esc_html(nextcore_field('nc_hero_brand', 'Nextcore')); ?></span></span></h1>
        <p class="hero-slogan"><?php echo esc_html(nextcore_field('nc_hero_slogan', 'BUILD TRUST, CREATE VALUE')); ?></p>
        <p class="hero-description"><?php echo esc_html(nextcore_field('nc_hero_description', 'Giải pháp phần mềm cho doanh nghiệp hiện đại.')); ?></p>
        <div class="button-row"><?php if (nextcore_contact_url()) : ?><a class="button" href="<?php echo esc_url(nextcore_contact_url()); ?>"><?php echo esc_html(nextcore_field('nc_hero_contact_label', 'Liên hệ ngay')); ?> <span aria-hidden="true">→</span></a><?php endif; ?><a class="button-text" href="#services"><span class="circle" aria-hidden="true">↗</span> <?php echo esc_html(nextcore_field('nc_hero_secondary_label', 'Xem năng lực')); ?></a></div>
      </div></div>
      <a class="scroll-cue" href="#about"><span><?php esc_html_e('Khám phá', 'nextcore-theme'); ?></span><span class="circle" aria-hidden="true">↓</span></a>
    </section>


