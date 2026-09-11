<?php

/**
 * The template for displaying 404 pages (Not Found)
 *
 * @package WordPress
 * @subpackage Twenty_Thirteen
 * @since Twenty Thirteen 1.0
 */

get_header(); ?>


<div id="pagetitle" class="page-title bg-image page-title-layout1">
    <canvas id="confetti" width="1344" height="919"></canvas>
    <div class="page-title-pattern">
        <img class="img-fluid" src="<?php echo bloginfo('template_url') . '/../flatsome-child/assets/images/bgr-pagetitle1.png'; ?>" alt="Nextcore Software">
    </div>
    <div class="container">
        <div class="page-title-inner">
            <div class="page-title-holder">
                <h1 class="page-title"><span class="custom-title-page"><?php echo get_first_text_title("Lỗi 404", 1); ?></span><?php echo get_first_text_title("Lỗi 404"); ?></h1>
                <!-- <div class="page-sub-title">Nextcore Software</div> -->
            </div>
            <?php echo do_shortcode('[breadcrumb_page]'); ?>
        </div>
    </div>
</div>
<div id="content" class="site-content">
    <div class="content-inner">
        <div id="primary" class="content-area">
            <main id="main" class="site-main">
                <section class="error-404 bg-overlay bg-image">
                    <div class="error-404-inner">
                        <div class="error-page">
                            <span>4</span>
                            <span class="rotateme">
                                <span class="screen-reader-text"></span>
                            </span>
                            <span>4</span>
                        </div>
                    </div>
                </section>
            </main>
        </div>
    </div>
</div>

<?php get_footer(); ?>