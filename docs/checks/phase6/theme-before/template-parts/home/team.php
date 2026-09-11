<?php defined('ABSPATH') || exit; ?>
<section class="team section-border" id="team" aria-labelledby="team-title"><div class="container team-inner">
      <div class="team-heading" data-reveal><p class="eyebrow"><?php echo esc_html(nextcore_field('nc_team_eyebrow', 'ĐỘI NGŨ')); ?></p><h2 id="team-title"><?php echo esc_html(nextcore_field('nc_team_heading', 'Đội ngũ')); ?></h2><p class="team-description"><?php echo esc_html(nextcore_field('nc_team_intro', 'Đội ngũ chuyên môn cao, tận tâm và luôn sẵn sàng đồng hành.')); ?></p></div>
      <div class="team-grid">
        <?php foreach (nextcore_rows('nc_team_members') as $row) :
    $name = $row['name'] ?? '';
    $email = sanitize_email($row['email'] ?? '');
    $phone = preg_replace('/[^+0-9]/', '', (string) ($row['phone'] ?? ''));
?>
<article class="team-card" data-reveal>
    <?php nextcore_image($row['image'] ?? 0, $name, '', 400, 400); ?>
    <div class="team-details"><h3><?php echo esc_html($name); ?></h3><p><?php echo esc_html($row['role'] ?? ''); ?></p></div>
    <div class="team-contacts">
    <?php if ($email) : ?><a href="<?php echo esc_url('mailto:' . $email); ?>" aria-label="<?php echo esc_attr(sprintf(__('Email %s', 'nextcore-theme'), $name)); ?>"><svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 6 9 7 9-7"/></svg></a><?php endif; ?>
    <?php if ($phone) : ?><a href="<?php echo esc_url('tel:' . $phone); ?>" aria-label="<?php echo esc_attr(sprintf(__('Gọi %s', 'nextcore-theme'), $name)); ?>"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7 3 4 4c-2 1-1 6 3 10s9 7 11 6l2-3-5-3-2 2c-3-1-5-3-6-6l2-2Z"/></svg></a><?php endif; ?>
    </div>
</article>
<?php endforeach; ?>
      </div>
    </div></section>

