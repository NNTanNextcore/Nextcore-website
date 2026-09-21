<?php
defined('ABSPATH') || exit;
$technology_groups = array(
    'platform' => array('label' => 'Platform', 'items' => array('drupal', 'salesforce', 'sitecore')),
    'language' => array('label' => 'Language', 'items' => array('python', 'csharp', 'java', 'php', 'javascript', 'apex')),
    'database' => array('label' => 'Database', 'items' => array('mysql', 'postgresql', 'sqlite', 'db2')),
    'framework' => array('label' => 'Framework', 'items' => array('flask', 'dotnet', 'laravel', 'react', 'vue')),
    'cloud' => array('label' => 'Cloud', 'items' => array('aws', 'azure', 'gcp')),
    'os' => array('label' => 'OS', 'items' => array('windows', 'ubuntu', 'centos')),
);
$technology_items = array();
$configured_items = nextcore_rows('nc_technology_items');
$fallback_items = nextcore_home_defaults()['nc_technology_items'];
$technology_heading = nextcore_field('nc_technology_heading', __("Nền tảng\ntạo nên khác biệt", 'nextcore-theme'));
$technology_heading_lines = preg_split('/\R+/', trim((string) $technology_heading));

// Keep configured labels and supply approved additions until they are saved through ACF.
foreach (array_merge($fallback_items, $configured_items) as $item) {
    $mark = $item['mark'] ?? '';
    if ($mark !== '') { $technology_items[$mark] = $item; }
}

$technology_icons = require get_template_directory() . '/inc/technology-icons.php';
$render_technology_node = static function ($mark, $label, $group_label, $index) use ($technology_icons) {
    if ($mark === 'aws') { $label = 'AWS'; }
    $icon_file = $technology_icons[$mark] ?? '';
    $icon_path = '/assets/images/technology/' . $icon_file;
    $has_icon = $icon_file !== '' && is_file(get_template_directory() . $icon_path);
    ?>
    <button class="<?php echo esc_attr('tech-node tech-node--' . $mark); ?>" type="button"
        aria-label="<?php echo esc_attr($label . ' — ' . $group_label); ?>"
        data-tech-node data-tech-label="<?php echo esc_attr($label); ?>" data-tech-group="<?php echo esc_attr($group_label); ?>"
        style="--node-index: <?php echo esc_attr((string) $index); ?>">
        <span class="tech-node-icon" aria-hidden="true">
            <?php if ($has_icon) : ?>
                <img src="<?php echo esc_url(get_template_directory_uri() . $icon_path); ?>" width="40" height="40" alt="" draggable="false" decoding="async">
            <?php else : ?>
                <span class="tech-node-wordmark"><?php echo esc_html($label); ?></span>
            <?php endif; ?>
        </span>
        <span class="tech-node-label" aria-hidden="true"><?php echo esc_html($label); ?></span>
    </button>
    <?php
};
?>
<section class="technology section-border" id="technology" aria-labelledby="technology-title">
    <div class="container technology-inner">
        <div class="technology-copy">
            <p class="eyebrow"><?php echo esc_html(nextcore_field('nc_technology_eyebrow', __('Công nghệ', 'nextcore-theme'))); ?></p>
            <h2 id="technology-title" class="translation-block"><?php foreach ($technology_heading_lines as $line) : ?><span><?php echo esc_html(trim($line)); ?></span><?php endforeach; ?></h2>
            <p class="technology-description"><?php esc_html_e('Chúng tôi lựa chọn và làm chủ những công nghệ hiện đại để kiến tạo giải pháp bền vững, hiệu quả và sẵn sàng cho tương lai.', 'nextcore-theme'); ?></p>
        </div>
        <div class="technology-constellation" role="region" aria-label="<?php esc_attr_e('Các công nghệ', 'nextcore-theme'); ?>" data-constellation>
            <svg class="constellation-connectors" aria-hidden="true" focusable="false"><g data-connector-layer></g></svg>
            <div class="constellation-core" aria-hidden="true"><span class="constellation-core-mark"><i></i><i></i><i></i></span><small>Nextcore</small></div>
            <div class="technology-clusters">
                <?php foreach ($technology_groups as $group_key => $group) : ?>
                    <section class="<?php echo esc_attr('tech-cluster tech-cluster--' . $group_key); ?>" data-tech-cluster="<?php echo esc_attr($group_key); ?>" aria-labelledby="tech-group-<?php echo esc_attr($group_key); ?>">
                        <h3 id="tech-group-<?php echo esc_attr($group_key); ?>"><?php echo esc_html($group['label']); ?></h3>
                        <div class="tech-cluster-nodes">
                            <?php foreach ($group['items'] as $index => $mark) :
                                if (!isset($technology_items[$mark])) { continue; }
                                $render_technology_node($mark, $technology_items[$mark]['label'] ?? $mark, $group['label'], $index);
                            endforeach; ?>
                        </div>
                    </section>
                <?php endforeach; ?>
            </div>
            <p class="constellation-status" aria-live="polite" data-constellation-status></p>
        </div>
    </div>
</section>
