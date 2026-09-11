<?php defined('ABSPATH') || exit; ?>
<section class="section section-projects container" id="projects" aria-labelledby="projects-title">
      <div class="section-heading" data-reveal><div><p class="eyebrow"><?php echo esc_html(nextcore_field('nc_projects_eyebrow', 'Sản phẩm nổi bật')); ?></p><h2 id="projects-title"><?php echo esc_html(nextcore_field('nc_projects_heading', 'Giải pháp được tin chọn')); ?></h2></div><?php if (nextcore_contact_url()) : ?><a class="text-link" href="<?php echo esc_url(nextcore_contact_url()); ?>"><?php echo esc_html(nextcore_field('nc_projects_contact_label', 'Trao đổi về dự án ')); ?><span aria-hidden="true">→</span></a><?php endif; ?></div>
      <div class="projects-grid">
        <?php $used = array(); foreach (nextcore_rows('nc_featured_projects') as $row) :
    $object = nextcore_project_object($row);
    if ($object && in_array($object->ID, $used, true)) { $object = null; }
    if ($object) { $used[] = $object->ID; }
    $url = $object ? nextcore_localized_url(get_permalink($object)) : '';
    $variant = in_array($row['visual_variant'] ?? '', array('portal', 'olympia', 'affiliate'), true) ? $row['visual_variant'] : 'portal';
    $title = !empty($row['card_label']) ? $row['card_label'] : ($object ? get_the_title($object) : '');
    $summary = !empty($row['card_summary']) ? $row['card_summary'] : ($object ? get_the_excerpt($object) : '');
    $screen_labels = array('portal' => 'Nextcore Portal', 'olympia' => 'Top Olympia', 'affiliate' => 'Affiliate');
    $tag = $url ? 'a' : 'article';
?>
<<?php echo $tag; ?> class="project-card" <?php if ($url) : ?>href="<?php echo esc_url($url); ?>"<?php endif; ?> data-reveal>
    <div class="project-visual <?php echo esc_attr($variant); ?>-visual">
        <?php if ($variant === 'affiliate') : ?><div class="wordpress-mark" aria-hidden="true">W</div><?php endif; ?>
        <div class="screen-frame"><div class="screen-bar"><i></i><i></i><i></i><span><?php echo esc_html($screen_labels[$variant]); ?></span></div>
        <?php nextcore_image(!empty($row['card_image']) ? $row['card_image'] : ($object ? get_post_thumbnail_id($object) : 0), $row['image_alt'] ?? $title, '', 1879, 914); ?>
        </div>
    </div>
    <div class="card-copy"><h3><?php echo esc_html($title); ?></h3><p><?php echo esc_html($summary); ?></p>
    <?php if ($url) : ?><span class="round-button" aria-hidden="true">→</span><?php endif; ?>
    </div>
</<?php echo $tag; ?>>
<?php endforeach; ?>
      </div>
    </section>


