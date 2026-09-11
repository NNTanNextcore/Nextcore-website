<?php defined('ABSPATH') || exit; ?>
<section class="section container" id="blog" aria-labelledby="blog-title"><div class="section-heading" data-reveal><div><p class="eyebrow"><?php echo esc_html(nextcore_field('nc_blog_eyebrow', 'Tin tức')); ?></p><h2 id="blog-title"><?php echo esc_html(nextcore_field('nc_blog_heading', 'Cập nhật mới nhất')); ?></h2></div><span class="quiet-label"><?php echo esc_html(nextcore_field('nc_blog_note', 'Góc nhìn & kiến thức')); ?></span></div>
      <?php $posts = new WP_Query(array('post_type' => 'post', 'post_status' => 'publish', 'posts_per_page' => 3, 'orderby' => array('date' => 'DESC', 'ID' => 'DESC'), 'ignore_sticky_posts' => true, 'no_found_rows' => true)); ?>
<div class="blog-grid">
<?php while ($posts->have_posts()) : $posts->the_post(); ?>
<article class="blog-card" data-reveal>
    <?php if (has_post_thumbnail()) { the_post_thumbnail('large', array('loading' => 'lazy')); } ?>
    <div><span class="post-meta"><?php $categories = get_the_category(); if ($categories) { echo esc_html($categories[0]->name); } ?></span>
    <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
    <p class="blog-excerpt"><?php echo esc_html(wp_trim_words(get_the_excerpt(), 26)); ?></p></div>
</article>
<?php endwhile; wp_reset_postdata(); ?>
</div></section>
