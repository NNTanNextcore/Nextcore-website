<?php defined('ABSPATH') || exit; ?>

<section class="final-cta" id="contact" aria-labelledby="cta-title">
    <?php nextcore_mode_image('cta', 'cta-image', 'Người chinh phục đỉnh núi nhìn về chân trời', 1536, 1024); ?>
    <div class="container cta-inner">
        <div data-reveal>
            <h2 id="cta-title"><?php echo esc_html(nextcore_field('nc_cta_heading', __('Sẵn sàng bắt đầu dự án của bạn?', 'nextcore-theme'))); ?></h2>
            <p><?php echo esc_html(nextcore_field('nc_cta_description', __('Hãy để Nextcore đồng hành cùng bạn trên hành trình chuyển đổi số.', 'nextcore-theme'))); ?></p>
            <?php if (nextcore_contact_url()) : ?>
                <a class="button" href="<?php echo esc_url(nextcore_contact_url()); ?>"><?php echo esc_html(nextcore_field('nc_cta_button_label', __('Liên hệ ngay', 'nextcore-theme'))); ?> <span aria-hidden="true">→</span></a>
            <?php endif; ?>
        </div>
    </div>
</section>
