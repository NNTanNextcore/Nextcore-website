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
                <h1 class="page-title page-title_custom"><?php echo get_the_title(); ?></h1>
                <!-- <div class="page-sub-title">Nextcore Software</div> -->
            </div>
            <?php echo do_shortcode('[breadcrumb_page]'); ?>
        </div>
    </div>
</div>
<div id="content" class="blog-wrapper blog-single page-wrapper">

    <?php
    /**
     * Posts layout right sidebar.
     *
     * @package          Flatsome\Templates
     * @flatsome-version 3.16.0
     */

    do_action('flatsome_before_blog');
    ?>

    <?php if (!is_single() && flatsome_option('blog_featured') == 'top') {
        get_template_part('template-parts/posts/featured-posts');
    } ?>

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
            <?php if (!is_single() && flatsome_option('blog_featured') == 'content') {
                get_template_part('template-parts/posts/featured-posts');
            } ?>
            <?php
            if (is_single()) {
            ?>
                <?php
                if (have_posts()) : ?>

                    <?php /* Start the Loop */ ?>
          
                    <?php while (have_posts()) : the_post(); ?>

                        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                            <div class="article-inner <?php flatsome_blog_article_classes(); ?>">
                                <?php
                                if (flatsome_option('blog_post_style') == 'default' || flatsome_option('blog_post_style') == 'inline') { ?>
                                    <header class="entry-header">
                                        <?php if (has_post_thumbnail()) : ?>

                                            <?php $images = get_field('anh_chi_tiet');  ?>

                                            <?php if (! is_single() || (is_single() && get_theme_mod('blog_single_featured_image', 1))) : ?>
                                                <div class="entry-image relative">
                                                    <?php if ($images):
                                                        get_image_list($images) ?>
                                                        <div id="inline-gallery-container" class="inline-gallery-container"></div>
                                                    <?php else : ?>
                                                        <?php get_template_part('template-parts/posts/partials/entry-image', 'default'); ?>
                                                        <?php if (get_theme_mod('blog_badge', 1)) get_template_part('template-parts/posts/partials/entry', 'post-date'); ?>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                        <div class="entry-header-text entry-header-text-top text-<?php echo get_theme_mod('blog_posts_title_align', 'center'); ?>">
                                            <?php get_template_part('template-parts/posts/partials/entry', 'title'); ?>
                                        </div>

                                    </header>
                                <?php
                                }
                                ?>
                                <?php get_template_part('template-parts/posts/content', 'single'); ?>
                            </div>
                        </article>

                    <?php endwhile; ?>

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
                <?php else : ?>

                    <?php get_template_part('no-results', 'index'); ?>

                <?php endif; ?>

            <?php
                comments_template();
            } elseif (flatsome_option('blog_style_archive') && (is_archive() || is_search())) {
                get_template_part('template-parts/posts/archive', flatsome_option('blog_style_archive'));
            } else {
                get_template_part('template-parts/posts/archive', flatsome_option('blog_style'));
            }
            ?>
        </div>
        <div class="post-sidebar close-slidebar col col-first sidebar-single-js">
            <?php flatsome_sticky_column_open('blog_sticky_sidebar'); ?>
            <?php get_sidebar(); ?>
        </div>
    </div>

    <?php
    do_action('flatsome_after_blog');
    ?>

</div>

<?php get_footer();
