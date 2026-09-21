<?php
defined('ABSPATH') || exit;

$team_plugin_settings = get_option('nextcore_team_settings', array());
$selected_team_ids = isset($team_plugin_settings['home_member_ids']) && is_array($team_plugin_settings['home_member_ids'])
    ? array_values(array_unique(array_filter(array_map('absint', $team_plugin_settings['home_member_ids']))))
    : array();
?>
<section class="team section-border" id="team" aria-labelledby="team-title"><div class="container team-inner nextcore-team-host">
      <div class="team-heading" data-reveal><p class="eyebrow"><?php echo esc_html(nextcore_field('nc_team_eyebrow', __('ĐỘI NGŨ', 'nextcore-theme'))); ?></p><h2 id="team-title"><?php echo esc_html(nextcore_field('nc_team_heading', __('Đội ngũ', 'nextcore-theme'))); ?></h2><p class="team-description"><?php echo esc_html(nextcore_field('nc_team_intro', __('Đội ngũ chuyên môn cao, tận tâm và luôn sẵn sàng đồng hành.', 'nextcore-theme'))); ?></p></div>
      <?php if (class_exists('Nextcore_Team_Renderer')) { echo Nextcore_Team_Renderer::render_selected_members($selected_team_ids); } // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
    </div></section>

