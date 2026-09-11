<?php
defined('ABSPATH') || exit;
$nextcore_languages = nextcore_language_links();
if (count($nextcore_languages) < 2) { return; }
?>
<nav class="nextcore-language-switch" data-no-translation aria-label="<?php esc_attr_e('Ngôn ngữ', 'nextcore-theme'); ?>">
<?php foreach ($nextcore_languages as $nextcore_language) :
    if (empty($nextcore_language['current_page_url']) || empty($nextcore_language['language_name'])) { continue; }
?>
    <a href="<?php echo esc_url($nextcore_language['current_page_url']); ?>" hreflang="<?php echo esc_attr(str_replace('_', '-', $nextcore_language['language_code'] ?? '')); ?>"><?php echo esc_html($nextcore_language['language_name']); ?></a>
<?php endforeach; ?>
</nav>

