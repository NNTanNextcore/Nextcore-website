<?php

/**
 * Template name: Page - Title custom
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
                <h1 class="page-title page-title_custom"><?php echo get_the_title(); ?></h1>
                <!-- <div class="page-sub-title">Nextcore Software</div> -->
            </div>
            <?php echo do_shortcode('[breadcrumb_page]'); ?>
        </div>
    </div>
</div>
<div id="content" class="content-area page-wrapper" role="main">
    <!-- <div class="row row-main"> -->
        <!-- <div class="large-12 col">
            <div class="col-inner"> -->

                <?php if (get_theme_mod('default_title', 0)) { ?>
                    <header class="entry-header">
                        <h1 class="entry-title mb uppercase"><?php the_title(); ?></h1>
                    </header>
                <?php } ?>

                <?php while (have_posts()) : the_post(); ?>
                    <?php do_action('flatsome_before_page_content'); ?>

                    <?php the_content(); ?>

                    <?php
                    if (comments_open() || get_comments_number()) {
                        comments_template();
                    }
                    ?>

                    <?php do_action('flatsome_after_page_content'); ?>
                <?php endwhile; // end of the loop. 
                ?>
            <!-- </div>
        </div> -->
    <!-- </div> -->
</div>

<?php
get_footer();

?>