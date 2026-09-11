<?php defined('ABSPATH') || exit; get_header(); ?>
<main id="main-content" class="nextcore-native nextcore-home">
<?php foreach (array('hero', 'about-video', 'stats', 'services', 'technology', 'projects', 'partner', 'testimonials', 'team', 'blog', 'cta') as $section) { get_template_part('template-parts/home/' . $section); } ?>
</main>
<?php get_footer(); ?>

