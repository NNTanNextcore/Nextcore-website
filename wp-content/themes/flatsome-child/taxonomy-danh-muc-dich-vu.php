<?php

/**
 * Template name: Page - Left Sidebar Custom
 *
 * @package          Flatsome\Templates
 * @flatsome-version 3.16.0
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
                <h1 class="page-title page-title_custom"><?php echo single_term_title('', false); ?></h1>
            </div>
            <?php echo do_shortcode('[breadcrumb_page]'); ?>
        </div>
    </div>
</div>

<div class="page-wrapper page-left-sidebar blog-archive ">
    <div class="row page_custom">
        <div class="large-9 col">
        <?php if(have_posts()) : ?>
            <div id="post-list">
                <div class="row large-columns-2 medium-columns- small-columns-1">
                <?php while(have_posts()) : the_post(); ?>
                    <div class="col post-item">
                        <div class="col-inner">
                            <div class="box box-text-bottom box-blog-post has-hover">
                                <div class="box-image">
                                    <div class="image-cover" style="padding-top:56%;">
                                        <a href="<?php the_permalink(); ?>" class="plain" aria-label="<?php echo get_the_title();?>">
                                            <?php echo get_the_post_thumbnail($post->ID, 'medium' ); ?>
                                        </a>
                                    </div>
                                </div>
                                <div class="box-text text-left">
                                    <div class="box-text-inner blog-post-inner">
                                        <h5 class="post-title is-large ">
                                            <a href="<?php the_permalink(); ?>" class="plain"><?php echo get_the_title();?></a>
                                        </h5>
                                        <div class="is-divider"></div>
                                        <p class="from_the_blog_excerpt "><?php the_excerpt(); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                <?php endwhile; ?> 
                <?php flatsome_posts_pagination(); ?>
                </div>
            </div>
            <?php else: ?>
                <?php get_template_part('template-parts/posts/content', 'single'); ?>
            <?php endif; ?>
        </div>

        <div class="large-3 col col-first ">
            <?php get_sidebar(); ?>
        </div>
    </div>
</div>

<?php get_footer(); ?>