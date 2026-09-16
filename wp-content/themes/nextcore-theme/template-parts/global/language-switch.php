<?php
defined('ABSPATH') || exit;
$nextcore_languages = nextcore_language_links();
if (count($nextcore_languages) < 2) { return; }
?>
<nav class="nextcore-language-switch" data-no-translation aria-label="<?php esc_attr_e('Ngôn ngữ', 'nextcore-theme'); ?>">
<?php foreach ($nextcore_languages as $nextcore_language) :
    if (empty($nextcore_language['current_page_url']) || empty($nextcore_language['language_name'])) { continue; }
    $nextcore_language_code = strtoupper(substr((string) ($nextcore_language['language_code'] ?? ''), 0, 2));
    $nextcore_language_label = $nextcore_language_code ?: wp_strip_all_tags((string) $nextcore_language['language_name']);
    $nextcore_language_name = wp_strip_all_tags((string) $nextcore_language['language_name']);
?>
    <a href="<?php echo esc_url($nextcore_language['current_page_url']); ?>" hreflang="<?php echo esc_attr(str_replace('_', '-', $nextcore_language['language_code'] ?? '')); ?>" title="<?php echo esc_attr($nextcore_language_name); ?>">
        <?php if (!empty($nextcore_language['flag_link'])) : ?>
            <img class="nextcore-language-flag" src="<?php echo esc_url($nextcore_language['flag_link']); ?>" alt="" width="18" height="12" loading="lazy">
        <?php endif; ?>
        <span><?php echo esc_html($nextcore_language_label); ?></span>
    </a>
<?php endforeach; ?>
</nav>

