<?php defined('ABSPATH') || exit; ?>
<section class="technology section-border" id="technology" aria-labelledby="technology-title"><div class="container technology-inner">
      <div><p class="eyebrow"><?php echo esc_html(nextcore_field('nc_technology_eyebrow', 'Công nghệ')); ?></p><h2 id="technology-title" class="translation-block"><?php echo nl2br(esc_html(nextcore_field('nc_technology_heading', "Nền tảng\n tạo nên khác biệt"))); ?></h2></div>
      <div class="marquee" role="region" aria-label="<?php esc_attr_e('Các công nghệ', 'nextcore-theme'); ?>"><div class="marquee-track"><ul class="tech-list"><?php foreach (nextcore_rows('nc_technology_items') as $row) : $mark = $row['mark'] ?? ''; ?>
<li class="<?php echo esc_attr($mark); ?>" aria-label="<?php echo esc_attr($row['label'] ?? ''); ?>"><?php
$default_labels = array('react' => 'React', 'laravel' => 'Laravel', 'node' => 'node.js', 'aws' => 'aws', 'mysql' => 'MySQL', 'docker' => 'docker', 'figma' => 'Figma', 'cicd' => 'CI/CD');
if (isset($default_labels[$mark]) && ($row['label'] ?? '') !== $default_labels[$mark]) {
    echo esc_html($row['label'] ?? '');
} else {
switch ($mark) {
case 'react': ?><span aria-hidden="true">⚛</span> React<?php break;
case 'laravel': ?><span aria-hidden="true">⬡</span> Laravel<?php break;
case 'node': ?>node<span class="small-tech">.js</span><?php break;
case 'aws': ?>aws<span class="aws-smile" aria-hidden="true"></span><?php break;
case 'mysql': ?>My<span>SQL</span><?php break;
case 'docker': ?><span aria-hidden="true">▦</span> docker<?php break;
case 'figma': ?><span class="figma-symbol" aria-hidden="true"><i></i><i></i><i></i><i></i><i></i></span> Figma<?php break;
case 'cicd': ?><span aria-hidden="true">∞</span> CI/CD<?php break;
}
}
?></li>
<?php endforeach; ?></ul></div></div>
      <button class="marquee-control icon-button" aria-label="<?php esc_attr_e('Tạm dừng chuyển động công nghệ', 'nextcore-theme'); ?>" aria-pressed="false"><span aria-hidden="true">Ⅱ</span></button>
    </div></section>
