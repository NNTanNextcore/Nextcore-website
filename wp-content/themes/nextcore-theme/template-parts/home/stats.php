<?php
defined('ABSPATH') || exit;

$stats_eyebrow = __('Dấu ấn Nextcore', 'nextcore-theme');
$stats_heading = __('Những con số tạo nên niềm tin', 'nextcore-theme');
$stats_description = __('Mỗi cột mốc là kết quả từ sự tin tưởng của khách hàng và nỗ lực bền bỉ của đội ngũ Nextcore.', 'nextcore-theme');
$stats_note_label = __('Hơn cả những con số', 'nextcore-theme');
$stats_note = __('Kinh nghiệm thực chiến, đội ngũ tận tâm và sự đồng hành dài hạn là nền tảng phía sau mỗi dự án được triển khai.', 'nextcore-theme');
?>
<section class="stats" id="stats" aria-labelledby="stats-title">
    <div class="container stats-inner">
        <header class="stats-heading" data-reveal>
            <p class="eyebrow"><?php echo esc_html(nextcore_field('nc_stats_eyebrow', $stats_eyebrow)); ?></p>
            <h2 id="stats-title"><?php echo esc_html(nextcore_field('nc_stats_heading', $stats_heading)); ?></h2>
            <p><?php echo esc_html(nextcore_field('nc_stats_description', $stats_description)); ?></p>
        </header>
        <div class="stats-grid">
            <?php foreach (nextcore_rows('nc_stats') as $index => $row) :
                $icons = array('projects' => 'layers', 'clients' => 'people', 'employees' => 'employee');
                $icon = $icons[$row['metric'] ?? ''] ?? 'layers';
                ?>
                <div class="stat" data-stat-index="<?php echo esc_attr(sprintf('%02d', $index + 1)); ?>">
                    <svg class="line-icon" aria-hidden="true"><use href="#i-<?php echo esc_attr($icon); ?>"/></svg>
                    <div>
                        <strong data-stat-value="<?php echo esc_attr(absint($row['value'] ?? 0)); ?>" data-stat-suffix="<?php echo esc_attr($row['suffix'] ?? ''); ?>"><?php echo esc_html(absint($row['value'] ?? 0) . ($row['suffix'] ?? '')); ?></strong>
                        <span><?php echo esc_html($row['label'] ?? ''); ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="stats-note" data-reveal>
            <span><?php echo esc_html(nextcore_field('nc_stats_note_label', $stats_note_label)); ?></span>
            <p><?php echo esc_html(nextcore_field('nc_stats_note', $stats_note)); ?></p>
        </div>
    </div>
</section>
