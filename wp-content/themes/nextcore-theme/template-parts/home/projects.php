<?php
defined('ABSPATH') || exit;

$product_query = new WP_Query(array(
    'post_type' => array('post', 'dich-vu'),
    'post_status' => 'publish',
    'posts_per_page' => 3,
    'orderby' => array(
        'menu_order' => 'ASC',
        'date' => 'DESC',
    ),
    'tax_query' => array(
        array(
            'taxonomy' => 'danh-muc-dich-vu',
            'field' => 'slug',
            'terms' => 'san-pham',
        ),
    ),
));
?>
<section class="section section-projects container" id="projects" aria-labelledby="projects-title">
    <div class="section-heading" data-reveal>
        <div>
            <p class="eyebrow"><?php echo esc_html(nextcore_field('nc_projects_eyebrow', 'Sản phẩm nổi bật')); ?></p>
            <h2 id="projects-title"><?php echo esc_html(nextcore_field('nc_projects_heading', 'Giải pháp được tin chọn')); ?></h2>
        </div>
    </div>

    <?php if ($product_query->have_posts()) : ?>
        <div class="projects-grid">
            <?php
            while ($product_query->have_posts()) :
                $product_query->the_post();
                $title = get_the_title();
                $summary = has_excerpt() ? get_the_excerpt() : wp_trim_words(wp_strip_all_tags(strip_shortcodes(get_the_content())), 18);
                ?>
                <a class="project-card" href="<?php echo esc_url(nextcore_localized_url(get_permalink())); ?>" data-reveal>
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="project-visual">
                            <?php the_post_thumbnail('medium_large', array('loading' => 'lazy')); ?>
                        </div>
                    <?php endif; ?>
                    <div class="card-copy">
                        <h3><?php echo esc_html($title); ?></h3>
                        <p><?php echo esc_html($summary); ?></p>
                    </div>
                </a>
                <?php
            endwhile;
            wp_reset_postdata();
            ?>
        </div>
    <?php endif; ?>
</section>
