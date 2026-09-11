<?php
defined('ABSPATH') || exit;
get_header();
?>
<main id="main-content">
<?php
if (have_posts()) {
    while (have_posts()) {
        the_post();
        get_template_part('template-parts/content/post');
    }
} else {
    get_template_part('template-parts/content/none');
}
?>
</main>
<?php get_footer(); ?>
