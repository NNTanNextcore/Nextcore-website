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
    <?php
    $platforms = array('facebook' => 'Facebook', 'tiktok' => 'TikTok', 'linkedin' => 'LinkedIn', 'youtube' => 'YouTube');
    $social_links = nextcore_rows('nc_social_links', 'option');
    $platform_order = array_flip(array_keys($platforms));
    usort($social_links, static function ($a, $b) use ($platform_order) {
        return ($platform_order[$a['platform'] ?? ''] ?? 99) <=> ($platform_order[$b['platform'] ?? ''] ?? 99);
    });
    foreach ($social_links as $social) :
        $platform = $social['platform'] ?? ''; $url = esc_url($social['url'] ?? ''); if (!$url || !isset($platforms[$platform])) { continue; } ?>
        <a href="<?php echo $url; ?>" aria-label="<?php echo esc_attr($platforms[$platform]); ?>">
            <?php if ($platform === 'facebook') : ?>
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M14 8h3V4h-3c-3.3 0-5 2-5 5v2H6v4h3v7h4v-7h3.2l.8-4h-4V9c0-.7.3-1 1-1Z"/></svg>
            <?php elseif ($platform === 'tiktok') : ?>
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M14 3v11.2a4.2 4.2 0 1 1-3.4-4.1v3.7a1.4 1.4 0 1 0 .6 1.2V3h2.8c.3 2 1.8 3.5 4 3.8v2.8A7.2 7.2 0 0 1 14 8Z"/></svg>
            <?php elseif ($platform === 'linkedin') : ?>
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 8H2v14h4V8Zm-.5-6a2.3 2.3 0 1 0 0 4.6 2.3 2.3 0 0 0 0-4.6ZM9 8v14h4v-7.5c0-2 2.6-2.2 2.6 0V22h4v-9c0-6-6.5-5.8-7.6-2.8V8Z"/></svg>
            <?php else : ?>
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="m9 8 7 4-7 4V8Zm12-2.5A2.5 2.5 0 0 0 18.5 3h-13A2.5 2.5 0 0 0 3 5.5v13A2.5 2.5 0 0 0 5.5 21h13a2.5 2.5 0 0 0 2.5-2.5v-13Z" fill-rule="evenodd"/></svg>
            <?php endif; ?>
            <span><?php echo esc_html($platforms[$platform]); ?></span>
        </a>
    <?php endforeach; ?>
    <?php $social_email = sanitize_email(nextcore_option('nc_contact_email', 'info@nextcore.vn')); if (is_email($social_email)) : ?>
        <a href="<?php echo esc_url('mailto:' . $social_email); ?>" aria-label="Gmail"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 6.5 12 13l9-6.5M4 5h16a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2Z"/></svg><span>Gmail</span></a>
    <?php endif; ?>
    </div><?php
    $footer_motto = nextcore_option('nc_footer_motto', 'BUILD TRUST, CREATE VALUE');
    if (strcasecmp(trim(preg_replace('/\s+/', ' ', $footer_motto)), 'Build a better tomorrow') === 0) {
        $footer_motto = 'BUILD TRUST, CREATE VALUE';
    }
    ?><p class="footer-motto"><?php echo nl2br(esc_html($footer_motto)); ?></p></div>
    <small class="footer-copyright">&copy; <?php echo esc_html(wp_date('Y')); ?> <?php esc_html_e('Nextcore. All rights reserved.', 'nextcore-theme'); ?></small>
</div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
