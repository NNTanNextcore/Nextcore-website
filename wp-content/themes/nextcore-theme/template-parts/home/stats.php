<?php defined('ABSPATH') || exit; ?>
<section class="stats" aria-label="<?php esc_attr_e('Nextcore qua những con số', 'nextcore-theme'); ?>">
<div class="container stats-grid">
<?php foreach (nextcore_rows('nc_stats') as $row) :
    $icons = array('projects' => 'layers', 'clients' => 'people', 'employees' => 'employee');
    $icon = $icons[$row['metric'] ?? ''] ?? 'layers';
?>
<div class="stat"><svg class="line-icon" aria-hidden="true"><use href="#i-<?php echo esc_attr($icon); ?>"/></svg><div><strong data-stat-value="<?php echo esc_attr(absint($row['value'] ?? 0)); ?>" data-stat-suffix="<?php echo esc_attr($row['suffix'] ?? ''); ?>"><?php echo esc_html(absint($row['value'] ?? 0) . ($row['suffix'] ?? '')); ?></strong><span><?php echo esc_html($row['label'] ?? ''); ?></span></div></div>
<?php endforeach; ?>
</div></section>
