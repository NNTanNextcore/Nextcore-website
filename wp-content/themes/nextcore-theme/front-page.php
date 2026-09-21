<?php
defined('ABSPATH') || exit;
get_header();
global $nextcore_footer_content_rendered;
?>
<main id="main-content" class="nextcore-native nextcore-home">
<?php foreach (array('hero', 'stats', 'services', 'technology', 'projects', 'partner', 'testimonials', 'team', 'blog') as $section) { get_template_part('template-parts/home/' . $section); } ?>
    <section class="home-contact-footer" aria-label="<?php esc_attr_e('Liên hệ và thông tin cuối trang', 'nextcore-theme'); ?>">
        <?php get_template_part('template-parts/home/cta'); ?>
        <div class="home-footer-slot">
            <?php
            get_template_part('template-parts/global/footer-content');
            $nextcore_footer_content_rendered = true;
            ?>
        </div>
    </section>
</main>
<?php get_footer(); ?>

