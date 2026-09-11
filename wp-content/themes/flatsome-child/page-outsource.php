<?php

/**
 * Template name: Page - Outsource
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
            </div>
            <?php echo do_shortcode('[breadcrumb_page]'); ?>
        </div>
    </div>
</div>
<div id="content" class="blog-wrapper blog-single page-wrapper">

    <div class="single-show-slidebar-custom container">
        <div class="single-show-slidebar-icon">
            <svg
                aria-hidden="true"
                class="e-font-icon-svg e-fas-th-list icon-show-slidebar-js"
                viewBox="0 0 512 512"
                xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M149.333 216v80c0 13.255-10.745 24-24 24H24c-13.255 0-24-10.745-24-24v-80c0-13.255 10.745-24 24-24h101.333c13.255 0 24 10.745 24 24zM0 376v80c0 13.255 10.745 24 24 24h101.333c13.255 0 24-10.745 24-24v-80c0-13.255-10.745-24-24-24H24c-13.255 0-24 10.745-24 24zM125.333 32H24C10.745 32 0 42.745 0 56v80c0 13.255 10.745 24 24 24h101.333c13.255 0 24-10.745 24-24V56c0-13.255-10.745-24-24-24zm80 448H488c13.255 0 24-10.745 24-24v-80c0-13.255-10.745-24-24-24H205.333c-13.255 0-24 10.745-24 24v80c0 13.255 10.745 24 24 24zm-24-424v80c0 13.255 10.745 24 24 24H488c13.255 0 24-10.745 24-24V56c0-13.255-10.745-24-24-24H205.333c-13.255 0-24 10.745-24 24zm24 264H488c13.255 0 24-10.745 24-24v-80c0-13.255-10.745-24-24-24H205.333c-13.255 0-24 10.745-24 24v80c0 13.255 10.745 24 24 24z"></path>
            </svg>
        </div>
    </div>

    <div class="row row-large <?php if (flatsome_option('blog_layout_divider')) echo 'row-divided '; ?>">

        <div class="large-12 right col body-single-js">



            <?php /* Start the Loop */ ?>

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

            <div class="single-footer-contact">
                <div class="single-footer-contact-flex row">
                    <div class="cms-heading-wrapper cms-heading-layout1 col large-6">
                        <h3 class="custom-heading">
                            <span>
                                LIÊN HỆ CHÚNG TÔI
                            </span>
                        </h3>

                        <div class="sub-heading-bottom">
                            Chúng tôi rất mong được bắt đầu một dự án với bạn! </div>
                        <div class="single-contact-custom">
                            <a href="/lien-he?service=<?php echo get_the_title(); ?>">Liên hệ ngay</a>
                        </div>
                    </div>
                    <div class="single-footer-contact-flex__img col large-6">
                        <img src="/wp-content/uploads/2024/11/20241107-084733.jpg" alt="nextcore">
                    </div>
                </div>
            </div>


        </div>
        <div class="post-sidebar close-slidebar col col-first sidebar-single-js">
            <?php flatsome_sticky_column_open('blog_sticky_sidebar'); ?>
            <?php get_sidebar(); ?>
        </div>
    </div>

</div>

<?php get_footer();
