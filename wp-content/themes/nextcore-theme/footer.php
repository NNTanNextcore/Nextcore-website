<?php defined('ABSPATH') || exit; ?>
<footer class="site-footer nextcore-native">
<div class="container footer-grid">
    <div class="footer-brand"><a class="brand" href="<?php echo esc_url(nextcore_home_url()); ?>"><img src="<?php echo esc_url(nextcore_asset('images/nextcore-logo.png')); ?>" width="200" height="90" alt="Nextcore"></a><p><?php echo esc_html(nextcore_option('nc_footer_description', 'Giải pháp công nghệ cho doanh nghiệp.')); ?></p></div>
    <?php foreach (array('about' => 'Về Nextcore', 'support' => 'Hỗ trợ') as $key => $label) : ?>
    <div><h2><?php echo esc_html(nextcore_option('nc_footer_' . $key . '_heading', $label)); ?></h2>
    <?php wp_nav_menu(array('theme_location' => 'footer_' . $key, 'container' => false, 'menu_class' => 'footer-menu', 'fallback_cb' => 'nextcore_menu_fallback')); ?>
    </div>
    <?php endforeach; ?>
    <div class="footer-contact"><h2><?php echo esc_html(nextcore_option('nc_footer_contact_heading', 'Liên hệ')); ?></h2><?php get_template_part('template-parts/global/contact-details'); ?></div>
    <div><h2><?php echo esc_html(nextcore_option('nc_footer_social_heading', 'Kết nối')); ?></h2><div class="social-links">
    <?php $platforms = array('facebook' => 'Facebook', 'linkedin' => 'LinkedIn', 'youtube' => 'YouTube', 'tiktok' => 'TikTok'); foreach (nextcore_rows('nc_social_links', 'option') as $social) :
        $platform = $social['platform'] ?? ''; $url = esc_url($social['url'] ?? ''); if (!$url || !isset($platforms[$platform])) { continue; } ?>
        <a href="<?php echo $url; ?>" aria-label="<?php echo esc_attr($platforms[$platform]); ?>"><?php echo esc_html($platforms[$platform]); ?></a>
    <?php endforeach; ?>
    </div><p class="footer-motto"><?php echo nl2br(esc_html(nextcore_option('nc_footer_motto', "Build a\nbetter tomorrow"))); ?></p></div>
    <small class="footer-copyright">&copy; <?php echo esc_html(wp_date('Y')); ?> <?php esc_html_e('Nextcore. All rights reserved.', 'nextcore-theme'); ?></small>
</div>
</footer>
<?php wp_footer(); ?>
</body>
</html>

