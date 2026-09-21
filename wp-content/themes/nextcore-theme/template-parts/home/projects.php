<?php
defined('ABSPATH') || exit;

$projects_intro_default = __('Khám phá những giải pháp thực tế được xây dựng từ nhu cầu vận hành và hướng đến giá trị bền vững.', 'nextcore-theme');
$projects_slogan = __('Sản phẩm thật - Giá trị thật - Đồng hành lâu dài', 'nextcore-theme');
$project_fallbacks = array('images/project-portal.png', 'images/project-olympia.png', 'images/project-affiliate.png');
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
            <p class="eyebrow"><?php echo esc_html(nextcore_field('nc_projects_eyebrow', __('Sản phẩm nổi bật', 'nextcore-theme'))); ?></p>
            <h2 id="projects-title"><?php echo esc_html(nextcore_field('nc_projects_heading', __('Giải pháp được tin chọn', 'nextcore-theme'))); ?></h2>
            <p class="projects-intro"><?php echo esc_html(nextcore_field('nc_projects_intro', $projects_intro_default)); ?></p>
        </div>
    </div>

    <?php if ($product_query->have_posts()) : ?>
        <div class="projects-grid">
            <?php
            $project_index = 0;
            while ($product_query->have_posts()) :
                $product_query->the_post();
                $title = get_the_title();
                $summary = has_excerpt() ? get_the_excerpt() : wp_trim_words(wp_strip_all_tags(strip_shortcodes(get_the_content())), 18);
                $thumbnail_id = get_post_thumbnail_id();
                $thumbnail_path = $thumbnail_id ? get_attached_file($thumbnail_id) : '';
                ?>
                <a class="project-card" href="<?php echo esc_url(nextcore_localized_url(get_permalink())); ?>" data-reveal>
                    <div class="project-visual">
                      <?php if ($thumbnail_path && is_readable($thumbnail_path)) : ?>
                        <?php the_post_thumbnail('large', array('loading' => 'lazy')); ?>
                      <?php else : ?>
                        <?php nextcore_image($project_fallbacks[$project_index % count($project_fallbacks)], $title, '', 1280, 720); ?>
                      <?php endif; ?>
                    </div>
                    <div class="card-copy">
                        <h3><?php echo esc_html($title); ?></h3>
                        <p><?php echo esc_html($summary); ?></p>
                    </div>
                </a>
                <?php
                $project_index++;
            endwhile;
            wp_reset_postdata();
            ?>
        </div>
        <p class="projects-slogan" data-reveal><?php echo esc_html($projects_slogan); ?></p>
    <?php endif; ?>
</section>
