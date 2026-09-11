<?php

/**
 * The blog template file.
 *
 * @package          Flatsome\Templates
 * @flatsome-version 3.16.0
 */

get_header();

?>

<div id="pagetitle" class="page-title bg-image page-title-layout1">
    <canvas id="confetti" width="1344" height="919"></canvas>
    <div class="page-title-pattern">
        <img class="img-fluid" src="<?php echo bloginfo('template_url') . '/../flatsome-child/assets/images/bgr-pagetitle1.png'; ?>" alt="Nextcore Software">
    </div>
    <div class="container">
        <div class="page-title-inner">
            <div class="page-title-holder">

                <?php
                $title = '';
                if (single_term_title('', false)) {
                    $title = single_term_title('', false);
                } else {
                    $title = "Blog";
                }
                ?>
                <h1 class="page-title page-title_custom"><?php echo $title; ?></h1>
                <!-- <div class="page-sub-title">Nextcore Software</div> -->
            </div>
            <?php echo do_shortcode('[breadcrumb_page]'); ?>
        </div>
    </div>
</div>
<div id="content" class="blog-wrapper blog-archive page-wrapper">

    <?php if (!is_single() && flatsome_option('blog_featured') == 'top') {
        get_template_part('template-parts/posts/featured-posts');
    } ?>

    <div class="row row-large <?php if (flatsome_option('blog_layout_divider')) echo 'row-divided '; ?>">

        <div class="large-9 col">
            <?php if (!is_single() && flatsome_option('blog_featured') == 'content') {
                get_template_part('template-parts/posts/featured-posts');
            } ?>
            <?php
            if (is_single()) {
                get_template_part('template-parts/posts/single');
                comments_template();
            } elseif (flatsome_option('blog_style_archive') && (is_archive() || is_search())) {
                get_template_part('template-parts/posts/archive', flatsome_option('blog_style_archive'));
            } else {
                get_template_part('template-parts/posts/archive', flatsome_option('blog_style'));
            }
            ?>
        </div>
        <div class="post-sidebar large-3 col">
            <?php flatsome_sticky_column_open('blog_sticky_sidebar'); ?>
            <?php get_sidebar(); ?>
            <?php flatsome_sticky_column_close('blog_sticky_sidebar'); ?>
        </div>
    </div>

    <?php
    do_action('flatsome_after_blog');
    ?>
</div>

<?php get_footer(); ?>