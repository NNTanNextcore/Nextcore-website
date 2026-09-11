<?php defined('ABSPATH') || exit; ?>
<section class="company-video section-border" id="about" aria-labelledby="about-title">
      <?php nextcore_mode_image('about', 'company-video-poster', '', 1672, 941); ?>
<video class="company-video-media" muted loop playsinline preload="none" aria-hidden="true" tabindex="-1"
    data-dark-src="<?php echo esc_url(nextcore_mode_url('nc_about_video_dark', 'video/nextcore-danang.mp4', true)); ?>"
    data-light-src="<?php echo esc_url(nextcore_mode_url('nc_about_video_light', 'video/caurongquay-light-video.mp4', true)); ?>"></video>
      <div class="container company-video-inner">
        <div class="company-video-copy">
          <p class="company-eyebrow" data-reveal><?php echo esc_html(nextcore_field('nc_about_eyebrow', 'Về Nextcore')); ?></p>
          <h2 id="about-title" data-reveal><span><?php echo esc_html(nextcore_field('nc_about_heading_line_one', 'Công ty Cổ phần')); ?></span> <span><?php echo esc_html(nextcore_field('nc_about_heading_line_two', 'Phần mềm Nextcore')); ?></span></h2>
          <?php
          $founded = nextcore_field('nc_about_founded_date', '2022-06-15');
          $date = DateTimeImmutable::createFromFormat('!Y-m-d', $founded, wp_timezone());
          if (!$date || $date->format('Y-m-d') !== $founded) {
              $founded = '2022-06-15';
              $date = new DateTimeImmutable($founded, wp_timezone());
          }
          $date_format = __('j \\t\\h\\g n, Y', 'nextcore-theme');
          // Gettext tracking markup must never be interpreted as date format tokens.
          if (is_callable(array('TRP_Translation_Manager', 'strip_gettext_tags'))) {
              $date_format = TRP_Translation_Manager::strip_gettext_tags($date_format);
          }
          ?>
          <p class="company-founded" data-reveal><?php esc_html_e('Thành lập vào', 'nextcore-theme'); ?> <time datetime="<?php echo esc_attr($founded); ?>"><?php echo esc_html(wp_date($date_format, $date->getTimestamp())); ?></time></p>
          <div class="company-description translation-block" data-reveal><?php echo wp_kses(nextcore_field('nc_about_body', '<p>Chuyên thực hiện phát triển, bảo trì các dự án CNTT cho các đối tác outsource.</p>
            <p>Đối tác của Công ty là các Công ty outsource lớn-vừa-nhỏ ở Việt Nam ở cả 3 thị trường nói tiếng Anh-Nhật-Việt.</p>
            <p>Không ngừng nỗ lực để giải quyết các vấn đề là <strong>Nỗi đau</strong> và tạo giá trị <strong>hữu ích</strong> cho khách hàng để trở thành đối tác tin cậy và lâu dài.</p>'), array('p' => array(), 'strong' => array(), 'em' => array(), 'a' => array('href' => array(), 'title' => array()))); ?></div>
          <a class="button company-more" href="#team" data-reveal><?php echo esc_html(nextcore_field('nc_about_link_label', 'Tìm hiểu thêm')); ?> <span aria-hidden="true">⟶</span></a>
        </div>
      </div>
    </section>
